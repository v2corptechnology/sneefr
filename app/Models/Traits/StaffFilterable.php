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
            $q->where('is_admin', true)->orWhereNull('user_id');
        });
    }
}
