<?php

namespace Sneefr\Jobs;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sneefr\Models\Ad;
use Sneefr\Repositories\Evaluation\EvaluationRepository;

class ForceOutdatedEvaluations extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EvaluationRepository $EvaluationRepository)
    {
        $outdated = Ad::onlyTrashed()
            ->whereNotNull('sold_to')
            ->whereDate('deleted_at', '>', Carbon::now()->subDays(11))
            ->whereDate('deleted_at', '<', Carbon::now()->subDays(10))
            ->with('evaluation')
            ->get();

        $outdated->each(function ($ad) use ($EvaluationRepository) {
            if ($ad->evaluation == null) {
                $EvaluationRepository->evaluate(
                    $ad->sold_to,
                    $ad->user_id,
                    $ad,
                    1,
                    null,
                    'forced'
                );
            }
        });
    }
}
