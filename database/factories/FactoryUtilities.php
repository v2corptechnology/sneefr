<?php

use Sneefr\Models\User;
use Sneefr\Models\Category;

if (!class_exists('FactoryUtilities')) {

    /**
     * A collection of utility methods to use in model factories.
     */
    class FactoryUtilities
    {
        /**
         * Get the identifier of a random existing User.
         *
         * @param  \Faker\Generator $faker
         * @param  string|null      $reason
         *
         * @return int
         *
         * @throws \Exception if no User exists
         */
        public static function randomUserIdentifier($faker, $reason = null)
        {
            if (is_null($reason)) {
                $reason = 'Factory needs at least one existing User to create an Ad';
            }

            $userIdentifiers = User::lists('id')->all();

            // If there is no existing user, abort.
            if (!$userIdentifiers) {
                throw new Exception($reason);
            }

            return $faker->randomElement($userIdentifiers);
        }

        /**
         * Get the identifier of a random existing Category.
         *
         * @param  \Faker\Generator $faker
         * @param  string|null      $reason
         *
         * @return int
         *
         * @throws \Exception if no Category exists
         */
        public static function randomCategoryIdentifier($faker, $reason = null)
        {
            if (is_null($reason)) {
                $reason = 'Factory needs at least one existing Category to create an Ad';
            }

            $categoryIdentifiers = Category::lists('id')->all();

            // If there is no existing user, abort.
            if (!$categoryIdentifiers) {
                throw new Exception($reason);
            }

            return $faker->randomElement($categoryIdentifiers);
        }
    }
}