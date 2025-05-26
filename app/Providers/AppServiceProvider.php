<?php

namespace App\Providers;

use App\Models\TimesheetDaily;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
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
        // Route::model('timesheet', TimesheetDaily::class);
        Schema::defaultStringLength(191);
    }
}
