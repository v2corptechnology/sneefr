<?php namespace Sneefr\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Ad;
use Sneefr\Models\Message;
use Sneefr\Models\Follow;
use Sneefr\Models\Share;
use Sneefr\Models\User;
use Sneefr\Services\Gamificator;

class UpdateRank extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    protected $achievedObjectives;

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param mixed $user
     */
    public function __construct($user)
    {
        if (is_int($user)) {
            $user = User::findOrFail($user);
        }

        $this->user = $user;
        $this->achievedObjectives = $this->getAchievedObjectives();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->setRank($this->getRankName());

        $this->user->setGamificationObjectives($this->achievedObjectives);

        $this->user->save();
    }

    /**
     * Return the achieved objectives.
     *
     * @return array
     */
    protected function getAchievedObjectives() : array
    {
        return collect()
            ->merge($this->getAchievedForUser())
            ->merge($this->getAchievedForAds())
            ->merge($this->getAchievedForFollows())
            ->merge($this->getAchievedForMessages())
            ->toArray();
    }

    /**
     * Get the rank name with those achieved objectives.
     *
     * @return string
     */
    protected function getRankName() : string
    {
        $gamificator = new Gamificator($this->achievedObjectives);

        return $gamificator->getRank();
    }

    /**
     * Get objectives relative to the user.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAchievedForUser()
    {
        $achieved = collect();

        if ($this->user->getLocation()) {
            $achieved->push(Gamificator::USER_FILLED_HIS_LOCATION);
        }

        if ($this->user->hasVerifiedEmail()) {
            $achieved->push(Gamificator::USER_HAS_VERIFIED_EMAIL);
        }

        if ($this->user->payment()->hasOne()) {
            $achieved->push(Gamificator::USER_ACTIVATED_PAYMENT);
        }

        if ($this->user->phone->isVerified()) {
            $achieved->push(Gamificator::USER_HAS_VERIFIED_PHONE);
        }

        return $achieved;
    }

    /**
     * Get objectives relative to ads.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAchievedForAds()
    {
        $objectives = collect();

        $adsCreated = Ad::withTrashed()->where('user_id', $this->user->getId())->get();
        $adsBought = Ad::onlyTrashed()->where('sold_to', $this->user->getId())->get();
        $adsShared = Share::where('user_id', $this->user->getId())->get();
        $adsSold = $adsCreated->filter(function ($ad) {
            return $ad->trashed() && $ad->sold_to;
        });

        if ($adsCreated->count()) {
            $objectives->push(Gamificator::USER_HAS_CREATED_AN_AD);
        }

        if ($adsSold->count()) {
            $objectives->push(Gamificator::USER_SOLD_AN_AD);
        }

        if ($adsBought->count()) {
            $objectives->push(Gamificator::USER_BOUGHT_AN_AD);
        }

        if ($adsShared->count()) {
            $objectives->push(Gamificator::USER_SHARED_AN_AD_ON_FACEBOOK);
        }

        return $objectives;
    }

    /**
     * Get objectives relative to follows.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAchievedForFollows()
    {
        $objectives = collect();

        $followedUsers = Follow::where('user_id', $this->user->getId())->where('followable_type','user')->get();
        $followedPlaces = $this->user->places;

        if ($followedUsers->count()) {
            $objectives->push(Gamificator::USER_FOLLOWS_A_SNEEFER);
        }

        if ($followedPlaces->count()) {
            $objectives->push(Gamificator::USER_FILLED_A_PLACE_OF_INTEREST);
        }

        return $objectives;
    }

    /**
     * Get objectives relative to messages.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAchievedForMessages()
    {
        $objectives = collect();

        $messagesSent = Message::where('from_user_id', $this->user->getId())->get();
        $messagesReceived = Message::where('to_user_id', $this->user->getId())->get();

        if ($messagesSent->count()) {
            $objectives->push(Gamificator::USER_SENT_A_MESSAGE);
        }

        if ($messagesReceived->count()) {
            $objectives->push(Gamificator::USER_RECEIVED_A_MESSAGE);
        }

        return $objectives;
    }
}
