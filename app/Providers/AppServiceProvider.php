<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\SiteSettingService;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteSettingService::class, static fn ($app): SiteSettingService => new SiteSettingService(
            $app->make(CacheRepository::class),
            Storage::disk('public'),
        ));
    }

    public function boot(): void
    {
        // Surface lazy loading (N+1 queries) and silently discarded attributes during development.
        Model::shouldBeStrict(! $this->app->isProduction());

        Password::defaults(function (): Password {
            $rule = Password::min(12)->mixedCase()->numbers()->symbols();

            return $this->app->isProduction() ? $rule->uncompromised() : $rule;
        });
    }
}
