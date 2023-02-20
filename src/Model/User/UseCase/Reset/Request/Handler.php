<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Request;

use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\ResetMailSender;
use App\Model\User\Service\ResetTokenGenerator;

class Handler
{
    private UserRepository $userRepository;
    private ResetTokenGenerator $tokenGenerator;
    private ResetMailSender $mailer;

    public function __construct(
        UserRepository $userRepository,
        ResetTokenGenerator $tokenGenerator,
        ResetMailSender $mailer
    )
    {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->mailer = $mailer;
    }

    public function handle(Command $command)
    {
        $user = $this->userRepository->getByEmail($command->email);

        $user->requestPasswordReset(
            $this->tokenGenerator->generate(),
            new \DateTimeImmutable()
        );

        $this->userRepository->flush();

        $this->mailer->send($user->getEmail(), $user->getResetToken());
    }
}
