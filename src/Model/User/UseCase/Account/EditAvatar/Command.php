<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\EditAvatar;

use App\Model\User\Entity\User\User;

class Command
{
    public User $user;
    public string $avatar;
    public string $avatarKey;
}
