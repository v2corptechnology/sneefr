<?php

namespace Sneefr\Repositories\Evaluation;

use Sneefr\Models\Evaluation;
use Sneefr\Models\Ad;
use Sneefr\Models\Shop;
use Sneefr\Models\User;

class EloquentEvaluationRepository implements EvaluationRepository
{
    /**
     * Store an evaluation from a user to another one related to an ad
     *
     * @param int $evaluatorId
     * @param int $assessedId
     * @param Ad $ad
     * @param bool $value
     * @param string $body (optional)
     * @param string $status (optional)
     *
     * @return bool
     */
    public function evaluate($evaluatorId, $assessedId, $ad, $value, $body = null, $status = 'waiting')
    {
        $data = [
            'user_id'      => $evaluatorId,
            'ad_id'        => $ad->getId(),
            'value'        => $value,
            'body'         => $body,
            'status'       => $status,
        ];

        if($ad->isInShop()){
            $shop = Shop::find($ad->getShopId());
            return $shop->evaluations()->create($data);
        }
        
        $user = User::find($assessedId);
        return $user->evaluations()->create($data);
        
    }

    /**
     * Delete all evaluations for an ad identifier
     *
     * @param int $adId
     *
     * @return bool
     */
    public function deleteForAd($adId)
    {
        return Evaluation::where('ad_id', $adId)->delete();
    }

    /**
     * Validate an evaluation for this ad and person identifier
     *
     * @param int $adId
     * @param int $evaluatorId
     *
     * @return bool
     */
    public function setValidFor($adId, $evaluatorId)
    {
        return Evaluation::where('ad_id', $adId)
            ->withTrashed()
            ->where('user_id', $evaluatorId)
            ->update(['status' => 'valid', 'deleted_at' => null]);
    }

    /**
     * Check if this user identifier has given an evaluation for this ad
     *
     * @param $evaluatorId
     * @param $adId
     *
     * @return bool
     */
    public function evaluationExistFor($evaluatorId, $adId)
    {
        return (bool) Evaluation::where('ad_id', $adId)
            ->where('user_id', $evaluatorId)
            ->count();
    }

}