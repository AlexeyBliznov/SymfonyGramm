<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\Create;

use App\Model\User\Entity\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public UserInterface $user;
    #[Assert\NotBlank]
    public string $firstName;
    #[Assert\NotBlank]
    public string $lastName;
    public string $biography;
    public string $avatar;
    public string $avatarKey;
}
