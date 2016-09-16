<?php namespace Sneefr\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\ActionLog;

class SaveSearch extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    /**
     * @var string
     */
    private $searched;

    /**
     * @var null|int
     */
    private $byUserId;

    /**
     * @var string
     */
    private $context;

    /**
     * Create a new job instance.
     *
     * @param $searched
     * @param null $byUserId
     * @param \Illuminate\Http\Request
     */
    public function __construct ($searched, $byUserId = null, Request $request)
    {
        $this->searched = $searched;
        $this->byUserId = $byUserId;
        $this->context = json_encode([
            'filters' => [
                $request->only(['q', 'category', 'type', 'condition'])
            ],
            'sort'    => $request->get('sort') . ' ' . $request->get('order'),
            'ip'      => $request->getClientIp(),
        ]);
    }

    /**
     * Execute the job.
     *
     * @return int
     */
    public function handle ()
    {
        return ActionLog::create([
            'type' => ActionLog::USER_SEARCH,
            'user_id' => $this->byUserId,
            'context' => $this->context,
        ]);
    }

}
