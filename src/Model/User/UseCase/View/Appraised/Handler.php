<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\Appraised;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $entityManager = $managerRegistry->getManager();
        $this->repository = $entityManager->getRepository(\App\Model\User\Entity\User\Like::class);
    }

    public function handle(Account $account): array
    {
        $likes = $this->repository->getByImageKey($account->getAvatarKey());
        $listOfAccounts = [];

        foreach ($likes as $value) {
            $listOfAccounts[] = $value->getAccount();
        }

        $result = [];

        foreach ($listOfAccounts as $value) {
            $result[$value->getUser()->getId()] = [
                'avatar' => $value->getAvatar(),
                'name' => $value->getFullName()
            ];
        }
        return $result;
    }
}
