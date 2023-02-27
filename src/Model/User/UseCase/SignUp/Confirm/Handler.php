<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm;

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
        if(!$user = $this->repository->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect token');
        }

        $user->confirmSignUp();

        $this->entityManager->flush();
    }
}
