<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Fix MySQL string length issue
        Schema::defaultStringLength(191);
        
        // Use custom Tailwind pagination views
        Paginator::defaultView('pagination.tailwind');
        Paginator::defaultSimpleView('pagination.tailwind');

        // Global view composer for authentication state
        view()->composer('*', function ($view) {
            $isAuthenticated = session('auth_user') !== null;
            $authUser = session('auth_user');
            
            if ($authUser) {
                $authUser = (object) $authUser;
            }

            $view->with([
                'isAuth' => $isAuthenticated,
                'authUser' => $authUser
            ]);
        });
    }
}