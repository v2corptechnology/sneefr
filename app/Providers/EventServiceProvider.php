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
        ],

        \Sneefr\Events\AdWasPurchased::class => [
            \Sneefr\Listeners\AdWasPurchased\EmailPurchaseConfirmationToSeller::class,
            \Sneefr\Listeners\AdWasPurchased\EmailPurchaseConfirmationToBuyer::class,
            \Sneefr\Listeners\AdWasPurchased\RemoveFromDiscussion::class,
            \Sneefr\Listeners\AdWasPurchased\ForgetAboutThisAd::class,
            \Sneefr\Listeners\AdWasPurchased\UpdateStock::class,
            \Sneefr\Listeners\AdWasPurchased\StoreSuccessfulDealConnections::class,
            \Sneefr\Listeners\AdWasPurchased\NotifyDealers::class,
            \Sneefr\Listeners\AdWasPurchased\StoreCharge::class,
        ],

        \Sneefr\Events\AdWasUpdated::class => [
            \Sneefr\Listeners\SendUpdatedNotification::class,
        ],

        \Sneefr\Events\MessageWasSent::class => [
            \Sneefr\Listeners\UpdateDiscussionStatus::class,
        ],

        \Sneefr\Events\UserRegistered::class => [
            \Sneefr\Listeners\UserRegistered\AddReferrals::class,
            \Sneefr\Listeners\UserRegistered\LogLogin::class,
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
