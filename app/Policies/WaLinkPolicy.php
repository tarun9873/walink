<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaLink;

class WaLinkPolicy
{
    public function update(User $user, WaLink $waLink)
    {
        return $user->id === $waLink->user_id;
    }

    public function delete(User $user, WaLink $waLink)
    {
        return $user->id === $waLink->user_id;
    }
}