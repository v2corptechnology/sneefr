<?php

namespace Sneefr\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;
use Sneefr\Models\Ad;

class EvaluationsController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * EvaluationsController constructor.
     *
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Display the form to leave an evaluation.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $key = $request->get('key');

        $this->guardAgainstInvalidEvaluationKey($key);

        $this->authorize('review', $this->ad);

        return view('evaluations.create')->with(['key' => $key, 'ad' => $this->ad]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->guardAgainstInvalidEvaluationKey($request->get('key'));

        $this->authorize('review', $this->ad);

        $data = [
            'user_id'      => auth()->id(),
            'ad_id'        => $this->ad->getId(),
            'value'        => $request->get('evaluation'),
            'body'         => $request->get('body'),
            'status'       => 'valid',
        ];


        if($this->ad->isInShop()){
            $shop = Shop::find($this->ad->getShopId());
            return $shop->evaluations()->create($data);
        }

        $user = User::find($this->getAssessedId($this->ad));
        $user->evaluations()->create($data);

        return redirect()
            ->route('home')
            ->with('success', trans('feedback.ad_evaluation_success'));
    }

    /**
     * Protect the evaluation from spoofing.
     *
     * @param string $key
     */
    protected function guardAgainstInvalidEvaluationKey(string $key)
    {
        // Decrypt the key
        $info = $this->encrypter->decrypt($key);

        // Check for well-formed info
        $this->guardAgainstInvalidFormat($info);

        // Check for expired link
        $this->guardAgainstExpiredEvaluation($info['expires_at']);

        // Check for existing evaluation
        $this->guardAgainstAlreadyGivenEvaluation($info['ad_id']);
    }

    /**
     * Check for well-formed info
     *
     * @param $info
     *
     * @throws \InvalidArgumentException
     */
    protected function guardAgainstInvalidFormat($info)
    {
        if (! is_array($info) || ! isset($info['ad_id']) || ! isset($info['expires_at'])) {
            throw new \InvalidArgumentException("The given key is not well formated");
        }
    }

    /**
     * Check if the expiration date is not passed.
     *
     * @param \Carbon\Carbon $date
     *
     * @throws \InvalidArgumentException
     */
    protected function guardAgainstExpiredEvaluation(Carbon $date)
    {
        if ($date->lt(Carbon::now())) {
            throw new \InvalidArgumentException("The evaluation key has expired");
        }
    }

    /**
     * Check the valuation is not existing yet.
     *
     * @param int $adId
     *
     * @throws \Exception
     */
    protected function guardAgainstAlreadyGivenEvaluation(int $adId)
    {
        $this->ad = Ad::withTrashed()->find($adId)->load('evaluation', 'shop', 'seller');

        if ($this->ad->evaluation != null) {
            throw new \Exception("The evaluation already exists");
        }
    }

    /**
     * Get wether the seller or the buyer according to the context.
     *
     * @param \Sneefr\Models\Ad $ad
     *
     * @return int
     */
    protected function getAssessedId(Ad $ad) : int
    {
        if ($ad->user_id == auth()->id()) {
            return (int) $ad->sold_to;
        }

        return (int) $ad->user_id;
    }
}
