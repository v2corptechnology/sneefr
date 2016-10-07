<?php

namespace Sneefr\Repositories\Report;

interface ReportRepository
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
    public function report($type, $id, $reporterId);

}
