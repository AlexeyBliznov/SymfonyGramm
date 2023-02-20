<?php

declare(strict_types=1);

namespace App\Tests\Unit\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignedUpUser();

        $now = new \DateTimeImmutable();
        $resetToken = new ResetToken('Token', $now->modify('+1 day'));
        $user->requestPasswordReset($resetToken, $now);

        $this->assertNotNull($user->getResetToken());
        $user->passwordReset($now, $hash = 'NewHash');

        $this->assertEquals($hash, $user->getPasswordHash());
    }

    public function testExpiredToken(): void
    {
        $user = $this->buildSignedUpUser();

        $now = new \DateTimeImmutable();
        $resetToken = new ResetToken('Token', $now->modify('+1 day'));
        $user->requestPasswordReset($resetToken, $now);

        $this->expectExceptionMessage('Reset token is expired');
        $user->passwordReset($now->modify('+2 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = $this->buildSignedUpUser();

        $now = new \DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested');
        $user->passwordReset($now, 'hash');
    }

    private function buildSignedUpUser(): User
    {
        $user = new User();
        $user->create(new Email('Email@email.com'),
            '1234',
            'token');
        return $user;
    }
}