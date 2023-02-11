<?php

namespace App\Providers;

use App\Models\NewsSyncDate;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\NewsCategoryRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\NewsSyncDateRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\NewsCategoryRepositoryInterface;
use App\Repositories\NewsRepositoryInterface;
use App\Repositories\NewsSyncDateRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RegisterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(NewsCategoryRepositoryInterface::class, NewsCategoryRepository::class);
        $this->app->bind(NewsSyncDateRepositoryInterface::class, NewsSyncDateRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
