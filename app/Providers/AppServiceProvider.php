<?php

namespace App\Providers;

use App\Http\View\Composers\PopularCourse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\FooterComposer;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        View::composer([
            'home.index',
            'home.profile',
            'home.courses-details',
            'home.events',
            'home.events-details',
            'home.register-teacher',
            'home.courses',
            'home.news',
            'home.news-details',
            'home.profile-details',
            'home.forgot-password',
            'home.reset-password',
            'home.change-password',
            'home.orders', 
            'home.qr-payment'
        ], FooterComposer::class);

        View::composer(['home.news-details', 'home.news'], PopularCourse::class);

        Paginator::useBootstrap();

        Relation::enforceMorphMap([
            'courses' => 'App\Models\Course',
            'events' => 'App\Models\Event',
        ]);
    }
}
