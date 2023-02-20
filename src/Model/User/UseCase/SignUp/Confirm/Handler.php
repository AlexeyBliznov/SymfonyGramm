<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm;

use App\Model\User\Entity\User\User;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function handle(Command $command): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $repository = $entityManager->getRepository(User::class);

        if(!$user = $repository->findByConfirmToken($command->token)) {
            throw new \DomainException('Incorrect token');
        }

        $user->confirmSignUp();

        $entityManager->flush();
    }
}
