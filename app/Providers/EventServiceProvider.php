<?php

namespace Sneefr\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Sneefr\Events\AdWasPosted::class => [
            \Sneefr\Listeners\AdWasPosted\MoveTemporaryImages::class,
            \Sneefr\Listeners\AdWasPosted\CopyAdLocationToProfile::class,
            \Sneefr\Listeners\AdWasPosted\AddCategoryToShop::class,
        ],

        \Sneefr\Events\AdWasPurchased::class => [
            \Sneefr\Listeners\AdWasPurchased\EmailPurchaseConfirmationToSeller::class,
            \Sneefr\Listeners\AdWasPurchased\EmailPurchaseConfirmationToBuyer::class,
            \Sneefr\Listeners\AdWasPurchased\UpdateStock::class,
            \Sneefr\Listeners\AdWasPurchased\SaveTransaction::class,
        ],

        \Sneefr\Events\ItemWasViewed::class => [
            \Sneefr\Listeners\ItemWasViewed\SaveView::class,
        ],

        \Sneefr\Events\MessageWasPosted::class => [
            \Sneefr\Listeners\MessageWasPosted\EmailMessage::class,
        ],

        \Sneefr\Events\UserRegistered::class => [
            \Sneefr\Listeners\UserRegistered\VerifyEmail::class,
            \Sneefr\Listeners\UserRegistered\LogLogin::class,
            \Sneefr\Listeners\UserRegistered\AddAvatar::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
