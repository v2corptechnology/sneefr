<?php namespace Sneefr\Repositories\Report;

use Sneefr\Models\Report;

class EloquentReportRepository implements ReportRepository
{
    /**
     * Report something either an Ad or a Person
     *
     * @param string $type Model name of the reported resource
     * @param int $id Identifier of the resource reported
     * @param int $reporterId Identifier of the reporting Person
     *
     * @return \Sneefr\Models\Report
     */
    public function report($type, $id, $reporterId)
    {
        $type = $type == 'profile' ? 'Sneefr\Models\User' : 'Sneefr\Models\Ad';

        return Report::create([
            'user_id'         => $reporterId,
            'reportable_id'   => $id,
            'reportable_type' => $type
        ]);
    }
}
