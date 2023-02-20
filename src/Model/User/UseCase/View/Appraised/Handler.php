<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\Appraised;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Account $account): array
    {
        $entityManager = $this->managerRegistry->getManager();
        $likeRepository = $entityManager->getRepository(\App\Model\User\Entity\User\Like::class);
        $likes = $likeRepository->getByImageKey($account->getAvatarKey());

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
