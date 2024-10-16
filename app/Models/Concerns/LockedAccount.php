<?php

namespace App\Models\Concerns;

use App\Notifications\AccountLockedNotification;
use Illuminate\Database\Eloquent\Builder;

trait LockedAccount
{
    /**
     * Send 'account locked' notification to user containing a link for unlocking.
     *
     * @return void
     */
    public function sendUnlockNotification(): void
    {
        $this->notify(new AccountLockedNotification);
    }

    /**
     * Append 'is_locked => true' condition.
     *
     * @param Builder $builder
     * @return void
     */
    public function scopeLocked(Builder $builder): void
    {
        $builder->where('is_locked', true);
    }

    /**
     * Check if the account is locked (find an account based on 'email').
     *
     * @param Builder $builder
     * @param string $email
     *
     * @return bool
     */
    public function scopeIsAccountLocked(Builder $builder, string $email): bool
    {
        return $builder->where('email', $email)->first()?->is_locked ?? false;
    }

    /**
     * Check if an account is locked (based on the data from the current model).
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    /**
     * Mark the account as locked.
     *
     * @return bool
     */
    public function markAsLocked(): bool
    {
        return $this->forceFill(['is_locked' => true])->save();
    }

    /**
     * Make the account as unlocked.
     *
     * @return bool
     */
    public function unlockAccount(): bool
    {
        return $this->forceFill(['is_locked' => false])->save();
    }
}
