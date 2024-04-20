<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function __construct(protected User $user)
    {}
}
