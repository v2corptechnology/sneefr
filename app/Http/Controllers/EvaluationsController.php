<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Models\Ad;
use Sneefr\Models\Evaluation;

class EvaluationsController extends Controller
{
    /**
     * Display the form to leave an evaluation.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $ad = Ad::withTrashed()->with('shop')->findOrFail($request->get('ad'));
        $shop = $ad->shop;

        $this->authorize('evaluate', [$shop, $ad]);

        return view('evaluations.create', compact('shop', 'ad'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $ad = Ad::withTrashed()->with('shop')->findOrFail($request->get('ad'));
        $shop = $ad->shop;

        $this->authorize('evaluate', [$shop, $ad]);

        Evaluation::pending()
            ->where('evaluator_id', auth()->id())
            ->where('shop_id', $shop->getId())
            ->where('ad_id', $ad->getId())
            ->update(['status' => Evaluation::STATUS_GIVEN]);

        return redirect()
            ->route('home')
            ->with('success', trans('feedback.ad_evaluation_success'));
    }
}
