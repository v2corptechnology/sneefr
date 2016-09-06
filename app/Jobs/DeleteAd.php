<?php

namespace Sneefr\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sneefr\Jobs\Job;
use Sneefr\Repositories\Ad\AdRepository;

class DeleteAd extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var int
     */
    private $adId;

    /**
     * Create a new job instance.
     *
     * @param int $adId
     */
    public function __construct($adId)
    {
        $this->adId = $adId;
    }

    /**
     * Remove the ad from database.
     *
     * @return \Sneefr\Models\Ad
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function handle(AdRepository $adRepository)
    {
        $ad = $adRepository->find($this->adId);

        // Delete all Ad relations
        $ad->likes()->delete();
        $ad->notifications()->delete();
        $ad->tags()->delete();
        
        return $ad->delete();

        //TODO: Delete files or not (check if ad was reported)
    }
}
