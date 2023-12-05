<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Transaction;
use App\Http\Resources\AdminResource;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DefaultCategoryResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\VcardResource;


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
        AdminResource::withoutWrapping();
        CategoryResource::withoutWrapping();
        DefaultCategoryResource::withoutWrapping();
        TransactionResource::withoutWrapping();
        VcardResource::withoutWrapping();
    }

    /**
 * Register any application services.
 */
}
