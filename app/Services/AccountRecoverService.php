<?php

namespace App\Services;

use App\Jobs\MailerJob;
use App\Mail\AccountRecover;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class AccountRecoverService
{
    public function __construct(protected User $user)
    {}

    public function sendRecoverEmail(string $email): void
    {
        /** @var User $user */
        $user = $this->user::getUserByEmail($email);
        MailerJob::dispatch(
            $user->email, new AccountRecover(Password::broker()->createToken($user))
        );
    }

    public function changePassword(string $email, string $password, string $token): ?User
    {
        /** @var User $user */
        $user = $this->user::getUserByEmail($email);
        if (!Password::broker()->tokenExists($user, $token)) {
            $user->password = bcrypt($password);
            $user->save();
            return $user;
        }
        return null;
    }
}
