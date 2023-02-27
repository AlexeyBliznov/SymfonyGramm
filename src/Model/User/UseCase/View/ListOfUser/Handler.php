<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\View\ListOfUser;

use App\Model\User\Entity\User\Account;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $repository;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $entityManager = $managerRegistry->getManager();
        $this->repository = $entityManager->getRepository(\App\Model\User\Entity\User\Account::class);
    }

    public function handle(Account $account): array
    {
        $accounts = $this->repository->getAllAccounts();

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
