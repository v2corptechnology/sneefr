<?php

namespace Sneefr\Http\Middleware;

use Closure;

class DeveloperMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowed = in_array(\Auth::user()
            ->facebook_id, config('sneefr.staff_facebook_ids.developers'));

        if (!$allowed) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
