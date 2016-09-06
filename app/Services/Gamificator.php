<?php namespace Sneefr\Services;

/**
 * Calculate the rank a user has, based on achieved goals.
 */
class Gamificator
{
    /*
     * Strings used to identify each objective, stored as is in DB
     */
    const USER_HAS_CREATED_AN_AD = 'ad.1';
    const USER_FILLED_HIS_LOCATION = 'user.location';
    const USER_HAS_VERIFIED_EMAIL = 'user.email';
    const USER_HAS_VERIFIED_PHONE = 'user.phone';
    const USER_FOLLOWS_A_SNEEFER = 'follow.1';
    const USER_SENT_A_MESSAGE = 'message.send.1';
    const USER_RECEIVED_A_MESSAGE = 'message.receive.1';
    const USER_SHARED_AN_AD_ON_FACEBOOK = 'share.facebook.1';
    const USER_BOUGHT_AN_AD = 'bought.1';
    const USER_SOLD_AN_AD = 'sold.1';
    const USER_ACTIVATED_PAYMENT = 'user.payment';
    const USER_FILLED_A_PLACE_OF_INTEREST = 'places.1';

    /**
     * @var array $achieved
     * Achieved goals
     */
    private $achieved;

    /**
     * @var string $rank
     * Current rank
     */

    private $rank;

    /**
     * @var array $ranks
     * The list of available ranks, sorted from lowest to highest.
     */
    public static $ranks = [
        'default' => [],
        'first'   => [
            self::USER_HAS_VERIFIED_EMAIL,
            self::USER_FILLED_A_PLACE_OF_INTEREST,
            self::USER_HAS_CREATED_AN_AD,
        ],
        'second'  => [
            self::USER_FOLLOWS_A_SNEEFER,
            //self::USER_SENT_A_MESSAGE,
            self::USER_SHARED_AN_AD_ON_FACEBOOK,
            //self::USER_RECEIVED_A_MESSAGE,
            self::USER_FILLED_HIS_LOCATION,
            self::USER_HAS_VERIFIED_PHONE,
        ],
        'third'   => [
            self::USER_BOUGHT_AN_AD,
            self::USER_SOLD_AN_AD,
        ],
        'fourth'  => [
            self::USER_ACTIVATED_PAYMENT,
        ],
    ];

    /**
     * The list objectives which are displayed as links.
     *
     * @var array $routes
     */
    protected static $routes = [
        self::USER_HAS_CREATED_AN_AD          => 'ad.create',
        self::USER_FILLED_HIS_LOCATION        => 'profiles.settings.edit',
        self::USER_FILLED_A_PLACE_OF_INTEREST => 'profiles.places.index',
        self::USER_HAS_VERIFIED_EMAIL         => 'profiles.settings.edit',
        self::USER_HAS_VERIFIED_PHONE         => 'profiles.settings.edit',
    ];

    /**
     * Build a new instance based on the already achieved goals.
     *
     * @param array $achieved
     */
    public function __construct(array $achieved = [])
    {
        // Total of objectives
        $allObjectives = collect(self::$ranks);

        // Store only the objectives that are  in ranks
        $this->achieved =  array_intersect($achieved, $allObjectives->flatten()->toArray());

        $this->rank = $this->calculateRank($achieved);
    }

    /**
     * Return name of the highest rank the current instance can meet.
     *
     * @return string
     */
    public function getRank() : string
    {
        return $this->rank;
    }

    /**
     * Return the name of the next rank to target.
     *
     * @return string
     */
    public function getNextRank() : string
    {
        // Get all ranks available
        $rankNames = array_keys(self::$ranks);

        // Position (index) of the next rank
        $nextRankIndex = array_search($this->getRank(), $rankNames) + 1;

        if ($nextRankIndex == count(self::$ranks)) {
            return $this->getRank();
        }

        // Get the next rank item after the current index
        $nextRank = array_slice(self::$ranks, $nextRankIndex, 1, true);

        // Retrieve the key name of the next rank item
        return key($nextRank);
    }

    /**
     * Return the highest rank name with the current achievements
     *
     * @param array $for
     *
     * @return string
     */
    public function calculateRank(array $for = []) : string
    {
        // By default, get the first rank
        $lastMatchedRankName = array_keys(self::$ranks)[0];

        foreach (self::$ranks as $rankName => $rankObjectives) {

            // Check how much objectives are missing for this rank
            $missing = count($rankObjectives) - count(array_intersect($for, $rankObjectives));

            // This rank doesn't pass, exit
            if ($missing) {
                break;
            }

            $lastMatchedRankName = $rankName;
        }

        // Return the highest filled rank
        return $lastMatchedRankName;
    }

    /**
     * Get the list of missing objectives to fill the next rank.
     *
     * @return array
     */
    public function getMissingObjectives() : array
    {
        $nextRankObjectives = self::$ranks[$this->getNextRank()];

        $missingObjectives = array_diff($nextRankObjectives, $this->achieved);

        return array_values($missingObjectives);
    }

    /**
     * Get the list already met objectives to fill the next rank.
     *
     * @return array
     */
    public function getAchievedObjectives() : array
    {
        $nextRankObjectives = self::$ranks[$this->getNextRank()];

        return array_intersect($this->achieved, $nextRankObjectives);
    }

    /**
     * Calculates the percentage completed before next rank
     *
     * @return int
     */
    public function getPercentageDone() : int
    {
        // Total of objectives
        $allObjectives = collect(self::$ranks);

        // Count only the objectives that are  in ranks
        $achieved = array_intersect($this->achieved, $allObjectives->flatten()->toArray());

        // What is the ratio of achieved vs. to achieve
        $ratio = count($achieved) / $allObjectives->flatten()->count();

        // Return a ratio on base 100
        return (int) round($ratio * 100);
    }


    /**
     * Get the color associated with the current gamification.
     *
     * @param int $percentage
     *
     * @return string
     */
    public function getPercentageColor(int $percentage = null) : string
    {
        if (is_null($percentage)) {
            $percentage = $this->getPercentageDone();
        }

        if ($percentage < 40) {
            return '#e74c3c';
        } elseif ($percentage < 60) {
            return '#e67e22';
        } elseif ($percentage < 80) {
            return '#f1c40f';
        } else {
            return '#2ecc71';
        }
    }

    /**
     * Check if next rank is available
     *
     * @return bool
     */
    public function hasNextRank() : bool
    {
        return $this->getRank() != $this->getNextRank();
    }

    /**
     * Get generated url for an objective.
     *
     * @param string $objective
     *
     * @return null|string
     */
    public function getObjectiveUrl($objective)
    {
        // No objective or no route is attached to this objective
        if (!$objective || !array_key_exists($objective, self::$routes)) {
            return null;
        }

        // Get the specified route value
        $routeName = self::$routes[$objective];

        if (is_array($routeName)) {
            return route($routeName['route'], auth()->user()) . $routeName['hash'];
        }

        return route($routeName, auth()->user());
    }
}
