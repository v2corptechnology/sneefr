<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Repositories\Report\ReportRepository;

class ReportController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request                                      $request
     * @param \Sneefr\Repositories\Report\ReportRepository $reportRepository
     *
     * @return \Sneefr\Http\Controllers\Response
     */
    public function store(Request $request, ReportRepository $reportRepository)
    {
        $type = $request->get('type', 'ad');

        if ($type == 'ad') {
            $id = $request->get('id');
        } else {
            $id = app('Hashids\Hashids')->decode($request->get('id'))[0];
        }

        $reportRepository->report($type, $id, auth()->id());

        return back()->with('success', trans('feedback.report_success'));
    }

}
