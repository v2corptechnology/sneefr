<?php namespace Sneefr\Listeners\AdWasPosted;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Session\Store as Session;
use Sneefr\Events\AdWasPosted;
use Sneefr\Jobs\UpdateRank;
use Sneefr\Repositories\User\UserRepository;

class CopyAdLocationToProfile
{
    use DispatchesJobs;

    /**
     * @param \Illuminate\Session\Store $session
     */
    protected $session;

    /**
     * @var \Sneefr\Repositories\User\UserRepository
     */
    private $userRepository;

    /**
     * Create new instance of event
     *
     * @param \Illuminate\Session\Store                $session
     * @param \Sneefr\Repositories\User\UserRepository $userRepository
     */
    public function __construct(Session $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    /**
     * Copy ad's location to the user if none filled.
     *
     * @param \Sneefr\Events\AdWasPosted $event
     */
    public function handle(AdWasPosted $event)
    {
        if ($event->ad->seller->getLatitude() && $event->ad->seller->getLongitude()) {
            return;
        }

        $this->userRepository->update([
            'id'       => $event->ad->seller->getId(),
            'location' => $event->ad->location(),
            'lat'      => $event->ad->latitude(),
            'long'     => $event->ad->longitude(),
        ]);

        // Update gamification objectives
        $this->dispatch(new UpdateRank(auth()->id()));

        $this->session->flash('success', trans(
            'feedback.copied_ad_location_to_your_profile',
            ['url' => route('profiles.show', $event->ad->seller) . '#settings']
        ));
    }
}
