<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\EditInfo;

use App\Model\User\Entity\User\User;

class Command
{
    public User $user;
    public string $firstName;
    public string $lastName;
    public string $biography;
}
