<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\MailSender;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\TokenGenerator;
use Doctrine\Persistence\ManagerRegistry;

class Handler
{
    private PasswordHasher $hasher;
    private TokenGenerator $generator;
    private MailSender $mailer;
    private ManagerRegistry $managerRegistry;

    public function __construct(
        PasswordHasher $hasher,
        TokenGenerator $generator,
        ManagerRegistry $managerRegistry,
        MailSender $mailer
    )
    {
        $this->hasher = $hasher;
        $this->generator = $generator;
        $this->managerRegistry = $managerRegistry;
        $this->mailer = $mailer;
    }

    public function handle(Command $command): void
    {
        $entityManager = $this->managerRegistry->getManager();
        $repository = $entityManager->getRepository(User::class);
        $email = new Email($command->email);

        if($repository->hasByEmail($email->getValue())) {
            throw new \DomainException('User already exists');
        }

        $user = new User();
        $user->create(
            $email,
            $this->hasher->hash($command->password),
            $token = $this->generator->generate()
        );

        $entityManager->persist($user);
        $this->mailer->send($email, $token);
        $entityManager->flush();
    }
}
