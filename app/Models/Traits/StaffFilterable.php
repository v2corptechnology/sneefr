<?php

namespace Sneefr\Models\Traits;

trait StaffFilterable
{
    /**
     * Filter the results based on the user identifiers
     */
    public function scopeExceptStaff($query)
    {
        return $query->where(function ($q) {
            $q->whereNotIn('user_id', config('sneefr.staff_facebook_ids.administrators'))->orWhereNull('user_id');
        });
    }
}
