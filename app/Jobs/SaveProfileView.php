<?php namespace Sneefr\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\ActionLog;

class SaveProfileView extends Job implements ShouldQueue
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
     * @param      $viewedId
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
        return ActionLog::create([
            'type'    => ActionLog::PROFILE_VIEW,
            'user_id' => $this->byUserId,
            'context' => json_encode(['id' => $this->viewedId])
        ]);
    }

}
