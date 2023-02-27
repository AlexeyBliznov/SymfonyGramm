<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\EditAvatar;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $repository;
    private ObjectManager $entityManager;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->entityManager = $managerRegistry->getManager();
        $this->repository = $this->entityManager->getRepository(\App\Model\User\Entity\User\Account::class);
    }

    public function handle(Command $command, string $avatar): void
    {
        $account = $this->repository->findByUser($command->user);

        $account->setAvatar($avatar);
        $account->setAvatarKey($command->avatarKey);

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
