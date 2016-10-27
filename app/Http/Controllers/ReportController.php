<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Sneefr\Http\Controllers\Response
     */
    public function store(Request $request)
    {
        $type = $request->get('type', 'ad');

        Report::create([
            'user_id'         => auth()->id(),
            'reportable_id'   => $request->get('id'),
            'reportable_type' => $type == 'profile' ? 'Sneefr\Models\User' : 'Sneefr\Models\Ad',
        ]);

        return back()->with('success', trans('feedback.report_success'));
    }

}
