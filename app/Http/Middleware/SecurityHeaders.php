<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds security headers to every response. The Content Security Policy only
 * allows our own assets plus the Google Analytics and Facebook Pixel hosts.
 * Inline scripts must carry the per-request nonce (Vite::cspNonce()).
 */
final class SecurityHeaders
{
    private const HSTS_MAX_AGE_SECONDS = 31536000;

    /**
     * Third-party hosts each CSP directive needs, besides 'self'.
     *
     * @var array<string, list<string>>
     */
    private const THIRD_PARTY_SOURCES = [
        'script-src' => [
            'https://*.googletagmanager.com',
            'https://connect.facebook.net',
        ],
        'img-src' => [
            'https://*.google-analytics.com',
            'https://*.googletagmanager.com',
            'https://www.facebook.com',
        ],
        'connect-src' => [
            'https://*.google-analytics.com',
            'https://*.analytics.google.com',
            'https://*.googletagmanager.com',
            'https://connect.facebook.net',
            'https://www.facebook.com',
        ],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $nonce = Vite::useCspNonce();

        $response = $next($request);

        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($nonce));
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=(), usb=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age='.self::HSTS_MAX_AGE_SECONDS.'; includeSubDomains',
            );
        }

        $response->headers->remove('X-Powered-By');

        return $response;
    }

    private function contentSecurityPolicy(string $nonce): string
    {
        $devServer = $this->viteDevServerOrigins();

        $directives = [
            'default-src' => ["'self'"],
            'script-src' => ["'self'", "'nonce-{$nonce}'", ...self::THIRD_PARTY_SOURCES['script-src'], ...$devServer],
            'style-src' => ["'self'", "'nonce-{$nonce}'", ...$devServer],
            'img-src' => ["'self'", 'data:', 'blob:', ...self::THIRD_PARTY_SOURCES['img-src'], ...$devServer],
            'font-src' => ["'self'", ...$devServer],
            'connect-src' => ["'self'", ...self::THIRD_PARTY_SOURCES['connect-src'], ...$devServer],
            // Only Google Maps embeds (Contact Us page and its admin preview).
            'frame-src' => ['https://www.google.com', 'https://maps.google.com'],
            'object-src' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'none'"],
        ];

        return collect($directives)
            ->map(static fn (array $sources, string $directive): string => $directive.' '.implode(' ', $sources))
            ->implode('; ');
    }

    /**
     * While "npm run dev" is running, assets are served by the Vite dev server,
     * which must be allowed too. Never applies to a production build.
     *
     * @return list<string>
     */
    private function viteDevServerOrigins(): array
    {
        if (! Vite::isRunningHot()) {
            return [];
        }

        $origin = rtrim((string) file_get_contents(public_path('hot')));

        return [$origin, (string) preg_replace('/^http/', 'ws', $origin)];
    }
}
