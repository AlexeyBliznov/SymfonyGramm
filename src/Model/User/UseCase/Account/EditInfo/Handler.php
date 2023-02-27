<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Account\EditInfo;

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

    public function handle(Command $command): void
    {
        $account = $this->repository->findByUser($command->user);

        $account->setFirstName($command->firstName);
        $account->setLastName($command->lastName);
        $account->setBiography($command->biography);

        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }
}
