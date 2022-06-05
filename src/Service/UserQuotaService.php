<?php

namespace App\Service;

use App\Entity\User;

class UserQuotaService
{
    public function checkNoQuota(User $user): bool
    {
        return $user->getItems()->count() >= $user->getQuota();
    }
}
