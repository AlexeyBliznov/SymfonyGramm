<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\ListOfUser;

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

        $repository = $entityManager->getRepository(\App\Model\User\Entity\User\Account::class);
        $accounts = $repository->getAllAccounts();

        $key = array_search($account, $accounts, true);
        unset($accounts[$key]);
        $result = [];

        foreach ($accounts as $value) {
            $result[$value->getUser()->getId()] = [
                'name' => $value->getFirstName() . ' ' . $value->getLastName(),
                'avatar' => $value->getAvatar()
            ];
        }
        return $result;
    }
}
