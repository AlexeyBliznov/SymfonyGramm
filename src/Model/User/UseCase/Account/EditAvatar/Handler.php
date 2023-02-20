<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\EditAvatar;

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
        $repository = $entityManager->getRepository(Account::class);

        $account = $repository->findByUser($command->user);

        $account->setAvatar($avatar);
        $account->setAvatarKey($command->avatarKey);

        $entityManager->persist($account);
        $entityManager->flush();
    }
}
