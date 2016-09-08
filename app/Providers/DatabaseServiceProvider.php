<?php namespace Sneefr\Providers;

use Illuminate\Support\ServiceProvider;
use Sneefr\Models\Ad;
use Sneefr\Models\Discussion;
use Sneefr\Models\Place;
use Sneefr\Models\Search;
use Sneefr\Models\Shop;
use Sneefr\Models\User;
use Sneefr\Repositories\Category\CachingCategoryRepository;
use Sneefr\Repositories\Category\CategoryRepository;
use Sneefr\Repositories\Category\EloquentCategoryRepository;
use Sneefr\Repositories\Notification\CachingNotificationRepository;
use Sneefr\Repositories\Notification\EloquentNotificationRepository;
use Sneefr\Repositories\Notification\NotificationRepository;
use Sneefr\Repositories\Search\CachingSearchRepository;
use Sneefr\Repositories\Search\EloquentSearchRepository;
use Sneefr\Repositories\Search\SearchRepository;

class DatabaseServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMorphMap();

        $this->app->singleton(CategoryRepository::class, function () {
            return new CachingCategoryRepository(new EloquentCategoryRepository(), $this->app['cache.store']);
        });

        $this->app->singleton(SearchRepository::class, function () {
            return new CachingSearchRepository(new EloquentSearchRepository(), $this->app['cache.store'], $this->app['auth.driver']);
        });

        $this->app->singleton(NotificationRepository::class, function () {
            return new CachingNotificationRepository(new EloquentNotificationRepository(), $this->app['cache.store']);
        });

        $this->app->bind(
            \Sneefr\Repositories\User\UserRepository::class,
            \Sneefr\Repositories\User\EloquentUserRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Ad\AdRepository::class,
            \Sneefr\Repositories\Ad\EloquentAdRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Discussion\DiscussionRepository::class,
            \Sneefr\Repositories\Discussion\EloquentDiscussionRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Evaluation\EvaluationRepository::class,
            \Sneefr\Repositories\Evaluation\EloquentEvaluationRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Report\ReportRepository::class,
            \Sneefr\Repositories\Report\EloquentReportRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Tag\TagRepository::class,
            \Sneefr\Repositories\Tag\EloquentTagRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Shop\ShopRepository::class,
            \Sneefr\Repositories\Shop\EloquentShopRepository::class);

        $this->app->bind(
            \Sneefr\Repositories\Place\PlaceRepository::class,
            \Sneefr\Repositories\Place\EloquentPlaceRepository::class);
    }

    /**
     * Register the morph map for polymorphic relations.
     *
     * @return void
     */
    protected function registerMorphMap()
    {
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'ad'         => Ad::class,
            'discussion' => Discussion::class,
            'place'      => Place::class,
            'search'     => Search::class,
            'shop'       => Shop::class,
            'user'       => User::class,
        ]);
    }
}
