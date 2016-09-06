<?php namespace Sneefr\Jobs;

use Carbon\Carbon;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Sneefr\Models\Ad;

class RemoveExpiredLocks extends Job implements ShouldQueue
{
    use DispatchesJobs, InteractsWithQueue, SerializesModels;

    /**
     * A mailer implementation.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    private $mailer;

    /**
     * Configuration repository.
     *
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     * @param \Illuminate\Config\Repository     $config
     *
     * @return void
     */
    public function handle(Mailer $mailer, Config $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;

        foreach ($this->getExpiredLocksOnAds() as $ad) {
            // Send the appropriate notifications
            $this->notify($ad);

            // Unlock the ad
            $ad->unlock();
        }
    }

    /**
     * Get ads that have an expired lock on it.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getExpiredLocksOnAds() : Collection
    {
        $minutesAfter = $this->config->get('sneefr.EXPIRE_AD_LOCK_AFTER');

        $olderThan = Carbon::now()->subMinutes($minutesAfter);

        return Ad::whereNotNull('locked_for')
            ->where('updated_at', '<', $olderThan)
            ->get();
    }

    /**
     * Send the appropriate notifications.
     *
     * @param \Sneefr\Models\Ad $ad
     */
    protected function notify(Ad $ad)
    {
        // Send to the buyer
        $this->dispatch(new SendExpiredLockEmail($ad));

        // Send to the seller
        $this->dispatch(new SendUnlockedAdEmail($ad));

        // Send the notifications to the seller
        $this->dispatch(new Notify($ad, Notify::SPECIAL));
    }
}
