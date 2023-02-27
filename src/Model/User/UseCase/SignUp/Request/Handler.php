<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\MailSender;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\TokenGenerator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private PasswordHasher $hasher;
    private TokenGenerator $generator;
    private MailSender $mailer;
    private ObjectRepository $repository;
    private ObjectManager $entityManager;

    public function __construct(
        PasswordHasher $hasher,
        TokenGenerator $generator,
        ManagerRegistry $managerRegistry,
        MailSender $mailer
    )
    {
        $this->hasher = $hasher;
        $this->generator = $generator;
        $this->mailer = $mailer;
        $this->entityManager = $managerRegistry->getManager();
        $this->repository = $this->entityManager->getRepository(\App\Model\User\Entity\User\User::class);
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if($this->repository->hasByEmail($email->getValue())) {
            throw new \DomainException('User already exists');
        }

        $user = new User();
        $user->create(
            $email,
            $this->hasher->hash($command->password),
            $token = $this->generator->generate()
        );

        $this->entityManager->persist($user);
        $this->mailer->send($email, $token);
        $this->entityManager->flush();
    }
}
