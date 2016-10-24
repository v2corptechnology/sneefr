<?php

namespace Sneefr\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Sneefr\Models\Ad;
use Sneefr\Models\ActionLog;
use Sneefr\Models\User;
use Sneefr\Repositories\Ad\AdRepository;
use Sneefr\Repositories\User\UserRepository;

class AdminController extends Controller
{
    public $reports;
    public $lastDay;
    public $yesterday;

    /**
     * @param \Sneefr\Repositories\User\UserRepository $userRepository
     * @param \Sneefr\Repositories\Ad\AdRepository $adRepository
     */
    public function __construct(UserRepository $userRepository, AdRepository $adRepository)
    {
        $this->yesterday = Carbon::now()->subDay();

        $this->reports = [
            'ads'   => $this->getReportedAds(),
            'users' => collect(),
        ];

        $this->lastDay = [
            'users.created' => User::whereDate('created_at', '>=', $this->yesterday)->get()->count(),
            'users.viewed'  => ActionLog::exceptStaff()->where('type', ActionLog::PROFILE_VIEW)->whereDate('created_at', '>=', $this->yesterday)->get()->count(),
            'ads.created'   => Ad::whereDate('created_at', '>=', $this->yesterday)->get()->count(),
            'ads.viewed'    => ActionLog::exceptStaff()->where('type', ActionLog::AD_VIEW)->whereDate('created_at', '>=', $this->yesterday)->get()->count(),
            'ads.sold'      => Ad::whereDate('created_at', '>=', $this->yesterday)->sold()->count(),
        ];

        $this->totals = [
            'users'           => User::exceptStaff()->withTrashed()->latest()->get()->count(),
            'ads'             => Ad::exceptStaff()->get()->count(),
            'ads.sold'        => Ad::exceptStaff()->sold()->onlyTrashed()->get()->count(),
            'ads.amount'      => Ad::exceptStaff()->sold()->onlyTrashed()->get()->sum('amount'),
            'reports'         => count($this->reports['ads']) + count($this->reports['users']),
            'searches'        => ActionLog::where('type', ActionLog::USER_SEARCH)->exceptStaff()->latest()->get()->count(),
            'shared_searches' => 0,
            'stripe_profiles' => 0,
        ];
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::exceptStaff()->withTrashed()->take(50)->latest()->get();

        $users = $this->retrieveAdCounts($users);

        return view('admin.users', [
            'users'   => $users,
            'reports' => $this->reports,
            'lastDay' => $this->lastDay,
            'totals'  => $this->totals,
        ]);
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function reported()
    {
        return view('admin.reported', [
            'reports' => $this->reports,
            'lastDay' => $this->lastDay,
            'totals' => $this->totals,
        ]);
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function ads()
    {
        $ads = Ad::exceptStaff()->take(30)->latest()->get();
        $ads = $ads->load(['user']);

        return view('admin.ads', [
            'ads'     => $ads,
            'reports' => $this->reports,
            'lastDay' => $this->lastDay,
            'totals'  => $this->totals,
        ]);
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function deals()
    {
        $ads = Ad::exceptStaff()->latest()->sold()->onlyTrashed()->take(30)->get();
        $ads = $ads->load(['user']);

        return view('admin.ads', [
            'ads'     => $ads,
            'reports' => $this->reports,
            'lastDay' => $this->lastDay,
            'totals'  => $this->totals,
        ]);
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function searches()
    {
        $shared = collect();

        $searched = ActionLog::where('type', ActionLog::USER_SEARCH)->exceptStaff()->latest()->take(5000)->get();
        $searched = $searched->load(['user']);
        $searched = $this->unduplicateSearches($searched, 100);

        return view('admin.searches', [
            'shared'   => $shared,
            'searched' => $searched,
            'reports'  => $this->reports,
            'lastDay'  => $this->lastDay,
            'totals'   => $this->totals,
        ]);
    }

    protected function retrieveAdCounts(Collection $users)
    {
        return $users->load([
            'ads' => function ($query) {
                $query->withTrashed();
            }
        ])->each(function ($item) {
            $item->deleted_ads = $item->ads->filter(function ($i) {
                return isset($i->deleted_at);
            })->count();

            $item->total_ads = $item->ads->count();

            $item->sold_ads = $item->ads->filter(function ($i) {
                return isset($i->sold_to);
            })->count();
        });
    }


    protected function unduplicateSearches(Collection $searches, $take = 100)
    {
        $searchesSubset = [];

        foreach ($searches as $k => $search) {

            $context = json_decode($search->context);

            if ($context->filters[0]->q) {

                $timestamp = strtotime($search->created_at);

                $timeSpan = $timestamp - $timestamp % (15 * 60);

                $slug = null;

                if (isset($context->filters[0])) {
                    $slug = crc32($context->filters[0]->q);
                }

                $key = $slug . '_' . $search->user_id . '_' . $timeSpan;

                if (!array_key_exists($key, $searchesSubset)) {
                    $search->term = isset($context->filters[0]) ? $context->filters[0]->q : null;
                    $searchesSubset[$key] = $search;
                }
                unset($searchesSubset[$k]);
            }
        }

        return array_slice($searchesSubset, 0, $take);
    }

    protected function getReportedAds()
    {
        return collect();
    }
}
