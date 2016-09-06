<?php namespace Sneefr\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;
use Sneefr\Models\Ad;
use Sneefr\Models\Evaluation;
use Sneefr\Models\User;
use Sneefr\Repositories\Evaluation\EvaluationRepository;

class EvaluationsController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * @var \Sneefr\Repositories\Ad\AdRepository
     */
    protected $adRepository;

    /**
     * @var \Sneefr\Models\Ad
     */
    protected $ad;

    /**
     * @var \Sneefr\Repositories\Evaluation\EvaluationRepository
     */
    protected $EvaluationRepository;

    /**
     * EvaluationsController constructor.
     *
     * @param \Illuminate\Contracts\Encryption\Encrypter $encrypter
     */
    public function __construct(Encrypter $encrypter, EvaluationRepository $EvaluationRepository)
    {
        $this->encrypter = $encrypter;
        $this->EvaluationRepository = $EvaluationRepository;
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

        $this->EvaluationRepository->evaluate(
            auth()->id(),
            $this->getAssessedId($this->ad),
            $this->ad,
            $request->get('evaluation'),
            $request->get('body'),
            'valid'
        );

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
