<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\Create;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class Handler
{
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $managerRegistry->getManager();
    }

    public function handle(Command $command, string $avatar): void
    {
        $account = new Account($command->user, $command->firstName, $command->lastName, $command->biography, $avatar, $command->avatarKey);

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
