<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\Create;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Command $command, string $avatar): void
    {
        $entityManager = $this->managerRegistry->getManager();

        $account = new Account($command->user, $command->firstName, $command->lastName, $command->biography, $avatar, $command->avatarKey);

        $entityManager->persist($account);
        $entityManager->flush();
    }
}
