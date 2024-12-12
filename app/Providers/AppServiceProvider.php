<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Number;
use App\Observers\WalletObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Validation\Rules\Password;
use App\Providers\TelescopeServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {

        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

    }

    public function boot(): void
    {
        Number::useCurrency('EUR');

        Number::macro('currencyCents', function (int|float $number, string $in = '', ?string $locale = null) {
            return Number::currency((int) $number / 100, $in, $locale);
        });

        Password::defaults(function () {
            return app()->isProduction() ? Password::min(8)
                ->numbers()
                ->mixedCase()
                ->uncompromised() : Password::min(6);
        });

        RateLimiter::for('api.login', function (Request $request) {
            return Limit::perMinutes(5, 5)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        Wallet::observe(WalletObserver::class);
    }
}
