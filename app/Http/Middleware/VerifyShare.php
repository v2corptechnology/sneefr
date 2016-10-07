<?php

namespace Sneefr\Http\Middleware;

use Closure;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Sneefr\Models\Share;

class VerifyShare
{
    use DispatchesJobs;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Display thank you for sharing message
        if ($request->has('post_id')) {
            \Session::flash('success', trans('feedback.ad_shared'));

            Share::create([
                'ad_id'   => $request->get('ad'),
                'user_id' => auth()->id(),
            ]);
        }

        return $next($request);
    }
}
