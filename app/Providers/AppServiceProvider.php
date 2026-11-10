<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\PageSectionService;
use App\Services\Images\WebpImageProcessor;
use App\Services\ProductService;
use App\Services\SiteSettingService;
use App\View\Composers\AdminNavigationComposer;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
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

        $this->app->bind(WebpImageProcessor::class, static fn (): WebpImageProcessor => new WebpImageProcessor(Storage::disk('public')));

        $this->app->singleton(PageSectionService::class, static fn ($app): PageSectionService => new PageSectionService(
            $app->make(CacheRepository::class),
            Storage::disk('public'),
            $app->make(WebpImageProcessor::class),
        ));

        $this->app->bind(ProductService::class, static fn ($app): ProductService => new ProductService(
            $app->make(WebpImageProcessor::class),
            Storage::disk('public'),
        ));
    }

    public function boot(): void
    {
        // Surface lazy loading (N+1 queries) and silently discarded attributes during development.
        Model::shouldBeStrict(! $this->app->isProduction());

        View::composer('components.admin.layout', AdminNavigationComposer::class);

        Password::defaults(function (): Password {
            $rule = Password::min(12)->mixedCase()->numbers()->symbols();

            return $this->app->isProduction() ? $rule->uncompromised() : $rule;
        });
    }
}
