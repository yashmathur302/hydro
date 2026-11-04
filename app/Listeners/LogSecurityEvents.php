<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Admin;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Writes authentication events to the "security" log channel.
 * Passwords, tokens and other secrets are never written.
 * Registered through Laravel's event discovery (handle* methods).
 */
final class LogSecurityEvents
{
    public function __construct(private readonly Request $request) {}

    public function handleLogin(Login $event): void
    {
        if ($event->user instanceof Admin) {
            $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
        }

        $this->log('info', 'Admin logged in', ['admin_id' => $event->user->getAuthIdentifier()]);
    }

    public function handleFailed(Failed $event): void
    {
        $this->log('warning', 'Admin login failed', ['email' => $event->credentials['email'] ?? null]);
    }

    public function handleLockout(Lockout $event): void
    {
        $this->log('warning', 'Admin login locked out after too many attempts', [
            'email' => $event->request->input('email'),
        ]);
    }

    public function handleLogout(Logout $event): void
    {
        $this->log('info', 'Admin logged out', ['admin_id' => $event->user?->getAuthIdentifier()]);
    }

    public function handlePasswordReset(PasswordReset $event): void
    {
        $this->log('notice', 'Admin password reset', ['admin_id' => $event->user->getAuthIdentifier()]);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function log(string $level, string $message, array $context): void
    {
        Log::channel('security')->log($level, $message, [
            ...$context,
            'ip' => $this->request->ip(),
            'user_agent' => mb_substr((string) $this->request->userAgent(), 0, 255),
        ]);
    }
}
