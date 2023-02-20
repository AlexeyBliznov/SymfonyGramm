<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;

class UserBuilder
{
    public function buildSignedUpUser(): User
    {
        $user = new User();
        $user->create(
            new Email('Email@email.com'),
            '1234',
            'token'
        );
        return $user;
    }
}