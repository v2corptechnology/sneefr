<?php

namespace Sneefr\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Sneefr\Models\Ad;
use Sneefr\Models\Evaluation;
use Sneefr\Models\Shop;
use Sneefr\Models\User;

class ShopPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Shop $shop)
    {
        if ($shop->trashed()) {
            $this->isShopOwner($user, $shop);
        }

        return true;
    }

    public function search(User $user, Shop $shop)
    {
        if ($shop->trashed()) {
            $this->isShopOwner($user, $shop);
        }

        return true;
    }

    public function evaluations(User $user, Shop $shop)
    {
        if ($shop->trashed()) {
            $this->isShopOwner($user, $shop);
        }

        return true;
    }

    public function edit(User $user, Shop $shop)
    {
        return $this->isShopOwner($user, $shop);
    }

    public function update(User $user, Shop $shop)
    {
        return $this->isShopOwner($user, $shop);
    }

    public function destroy(User $user, Shop $shop)
    {
        return $this->isShopOwner($user, $shop) && $user->isAdmin() && ! app()->environment('local', 'staging');
    }

    /**
     * Check if the current user can evaluate this shop
     * for a specific item
     *
     * @param \Sneefr\Models\User $user
     * @param \Sneefr\Models\Shop $shop
     * @param \Sneefr\Models\Ad $ad
     *
     * @return bool
     */
    public function evaluate(User $user, Shop $shop, Ad $ad)
    {
        // Is there a pending evaluation for this shop ?
        $evaluation = Evaluation::pending()
            ->where('evaluator_id', $user->getId())
            ->where('shop_id', $shop->getId())
            ->where('ad_id', $ad->getId())
            ->first();

        if (! $evaluation) {
            abort(403, "Either this evaluation expired either you are not authorized to review this shop");
        }

        return true;
    }

    private function isShopOwner(User $user, Shop $shop) : bool
    {
        return $shop->isOwner($user->getId());
    }
}
