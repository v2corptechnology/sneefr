<?php namespace Sneefr\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\ActionLog;

class SaveAdView extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    private $viewedId;
    /**
     * @var null
     */
    private $byUserId;

    /**
     * Create a new job instance.
     *
     * @param $viewedId
     * @param null $byUserId
     */
    public function __construct($viewedId, $byUserId = null)
    {
        $this->viewedId = $viewedId;
        $this->byUserId = $byUserId;
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle()
    {
        $user_id = $this->byUserId ? $this->byUserId : null;

        return ActionLog::create([
            'type'    => ActionLog::AD_VIEW,
            'user_id' => $user_id,
            'context' => json_encode(['id' => $this->viewedId])
        ]);
    }

}
