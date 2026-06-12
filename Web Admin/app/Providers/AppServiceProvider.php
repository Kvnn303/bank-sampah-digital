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
        \App\Models\Tabungan::observe(\App\Observers\TabunganObserver::class);
        \App\Models\Penarikan::observe(\App\Observers\PenarikanObserver::class);

        // Share notifications untuk admin layout
        View::composer('layouts.admin', function ($view) {
            // Hanya jalankan jika user sudah login dan merupakan admin
            if (!auth()->check() || !auth()->user()->isAdmin()) {
                $view->with([
                    'notifications' => collect(),
                    'unreadCount'   => 0,
                ]);
                return;
            }

            $adminId = auth()->id();

            // Ambil notifikasi untuk admin (target_role = 'admin' dan user_id = null atau user_id = admin yang login)
            $notifications = Notification::forAdmin($adminId)
                                ->latest()
                                ->take(10)
                                ->get();

            $unreadCount = Notification::forAdmin($adminId)
                                ->unread()
                                ->count();

            $view->with(compact('notifications', 'unreadCount'));
        });
    }
}
