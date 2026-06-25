<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Darryldecode\Cart\Facades\CartFacade as Cart;

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
