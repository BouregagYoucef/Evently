<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce strict mode to catch performance issues (N+1 queries) in dev
        \Illuminate\Database\Eloquent\Model::shouldBeStrict(! $this->app->isProduction());

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->role === 'superadmin' ? true : null;
        });
    }
}
