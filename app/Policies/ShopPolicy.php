<?php namespace Sneefr\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
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

    private function isShopOwner(User $user, Shop $shop) : bool
    {
        return $shop->isOwner($user->getId());
    }
}
