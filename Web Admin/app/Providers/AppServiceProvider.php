<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

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
        // Diperbaiki: 'layouts.app' diubah menjadi 'layouts.admin'
        View::composer('layouts.admin', function ($view) {
            $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();
            $unreadCount = Notification::unread()->count();
            $view->with(compact('notifications', 'unreadCount'));
        });
    }
}
