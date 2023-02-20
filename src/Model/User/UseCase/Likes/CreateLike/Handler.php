<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Likes\CreateLike;

use App\Model\User\Entity\User\Like;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(int $id): void
    {
        $entityManager = $this->managerRegistry->getManager();

        $repository = $entityManager->getRepository(\App\Model\User\Entity\User\User::class);
        $user = $repository->getById($id);

        $account = $user->getAccount();

        $like = new Like($account, $account->getAvatarKey());

        $entityManager->persist($like);
        $entityManager->flush();
    }
}
