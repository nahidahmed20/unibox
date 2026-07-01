<?php

namespace App\Providers;

use App\Models\Category;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // if (env('APP_ENV') === 'production') {
        //     URL::forceScheme('https');
        // }
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        View::composer('frontend.partials.header', function ($view) {
            $categories = Category::where('status', 1)->get();
            $view->with('categories', $categories);
        });

        View::composer('frontend.partials.header', function ($view) {
            $cart = session('cart', []);
            $view->with('cartCount', count($cart));
        });
        
    }
}
