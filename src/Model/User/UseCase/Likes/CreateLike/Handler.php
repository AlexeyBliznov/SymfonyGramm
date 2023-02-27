<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Likes\CreateLike;

use App\Model\User\Entity\User\Account;
use App\Model\User\Entity\User\Like;
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
        $this->repository = $this->entityManager->getRepository(\App\Model\User\Entity\User\User::class);
    }

    public function handle(int $id, Account $actualAccount): void
    {
        $user = $this->repository->getById($id);

        $account = $user->getAccount();

        $like = new Like($actualAccount, $account->getAvatarKey());

        $this->entityManager->persist($like);
        $this->entityManager->flush();
    }
}
