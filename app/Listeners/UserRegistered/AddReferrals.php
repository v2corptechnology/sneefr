<?php namespace Sneefr\Listeners\UserRegistered;

use Sneefr\Events\UserRegistered;
use Sneefr\Models\Follow;
use Sneefr\Models\Referral;
use Sneefr\Repositories\User\UserRepository;
use Sneefr\Services\FacebookConnector;

class AddReferrals
{
    /**
     * @var \Sneefr\Listeners\UserRegistered\FacebookConnector
     */
    private $connector;

    /**
     * @var \Sneefr\Repositories\User\UserRepository
     */
    private $userRepository;

    /**
     * Create the event listener.
     *
     * @param \Sneefr\Listeners\UserRegistered\FacebookConnector $connector
     * @param \Sneefr\Repositories\User\UserRepository           $userRepository
     */
    public function __construct(FacebookConnector $connector, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->connector = $connector;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $this->connector->setToken($event->user->token);

        $friends = $this->connector->getFriends();

        $registeredFriendsIds = $this->userRepository->getBySocialNetworkIds(array_keys($friends))->pluck('id')->all();

        $exisitingReferrals = Referral::where('referent_user_id', $event->user->getId())
            ->whereIn('referred_user_id', $registeredFriendsIds)
            ->pluck('referred_user_id')->all();

        $missingReferredIds = collect($registeredFriendsIds)->diff($exisitingReferrals);

        foreach ($missingReferredIds as $missingReferredId) {
            Referral::updateOrCreate([
               'referent_user_id' => $event->user->getId(),
               'referred_user_id' => $missingReferredId,
            ]);

            Referral::updateOrCreate([
                'referent_user_id' => $missingReferredId,
                'referred_user_id' => $event->user->getId(),
            ]);

            Follow::create([
                'user_id' => $event->user->getId(),
                'followable_id' => $missingReferredId,
                'followable_type' => 'user',
            ]);

            Follow::create([
                'user_id' => $missingReferredId,
                'followable_id' => $event->user->getId(),
                'followable_type' => 'user',
            ]);

            //$this->shop->followers()->attach(auth()->id());
        }

        // use sync method ? $cart->items()->sync([$item->id], false);
    }
}
