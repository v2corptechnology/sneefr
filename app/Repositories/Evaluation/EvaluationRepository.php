<?php namespace Sneefr\Repositories\Evaluation;

interface EvaluationRepository
{
    /**
     * Store an evaluation from a user to another one related to an ad
     *
     * @param int $evaluatorId
     * @param int $assessedId
     * @param string $type
     * @param Ad $ad
     * @param bool $value
     * @param string $body (optional)
     * @param string $status (optional)
     *
     * @return bool
     */
    public function evaluate($evaluatorId, $assessedId, $ad, $value, $body = null, $status = null);

    /**
     * Delete all evaluations for an ad identifier
     *
     * @param int $adId
     *
     * @return bool
     */
    public function deleteForAd($adId);

    /**
     * Validate an evaluation for this ad and person identifier
     *
     * @param int $adId
     * @param int $evaluatorId
     *
     * @return bool
     */
    public function setValidFor($adId, $evaluatorId);

    /**
     * Check if this user identifier has given an evaluation for this ad
     *
     * @param $evaluatorId
     * @param $adId
     *
     * @return bool
     */
    public function evaluationExistFor($evaluatorId, $adId);

}
