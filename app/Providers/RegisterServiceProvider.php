<?php

namespace App\Providers;

use App\Models\NewsCategory;
use App\Models\NewsSyncDate;
use App\Repositories\ContributorRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\ContributorRepository;
use App\Repositories\Eloquent\NewsCategoryRepository;
use App\Repositories\Eloquent\NewsContributorRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\NewsSyncDateRepository;
use App\Repositories\Eloquent\SourceRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\NewsCategoryRepositoryInterface;
use App\Repositories\NewsContributorRepositoryInterface;
use App\Repositories\NewsRepositoryInterface;
use App\Repositories\NewsSyncDateRepositoryInterface;
use App\Repositories\SourceRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\IContributorService;
use App\Services\Implementation\ContributorService;
use App\Services\Implementation\NewsCategoryService;
use App\Services\Implementation\NewsService;
use App\Services\Implementation\SourceService;
use App\Services\Implementation\UserService;
use App\Services\INewsCategoryService;
use App\Services\INewsService;
use App\Services\ISourceService;
use App\Services\IUserService;
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
        $this->app->bind(ContributorRepositoryInterface::class, ContributorRepository::class);
        $this->app->bind(NewsContributorRepositoryInterface::class, NewsContributorRepository::class);
        $this->app->bind(SourceRepositoryInterface::class, SourceRepository::class);

        $this->app->bind(IContributorService::class, ContributorService::class);
        $this->app->bind(ISourceService::class, SourceService::class);
        $this->app->bind(INewsService::class, NewsService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(INewsCategoryService::class, NewsCategoryService::class);
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
