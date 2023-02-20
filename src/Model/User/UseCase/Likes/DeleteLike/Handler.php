<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Likes\DeleteLike;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(int $id, Account $actualAccount): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $repository = $entityManager->getRepository(\App\Model\User\Entity\User\User::class);
        $user = $repository->getById($id);

        $account = $user->getAccount();

        $likeRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Like::class);
        $likeRepository->deleteLike($actualAccount, $account->getAvatarKey());
    }
}
