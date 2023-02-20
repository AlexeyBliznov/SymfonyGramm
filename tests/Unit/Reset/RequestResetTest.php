<?php

declare(strict_types=1);

namespace App\Tests\Unit\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;
use App\Tests\Unit\UserBuilder;
use Monolog\Test\TestCase;

class RequestResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $builder = new UserBuilder();
        $now = new \DateTimeImmutable();
        $token = new ResetToken('Token', $now->modify('+1 day'));

        $user = $builder->buildSignedUpUser();

        $user->requestPasswordReset($token, $now);

        $this->assertNotNull($user->getResetToken());
    }

    public function testAlready(): void
    {
        $builder = new UserBuilder();
        $now = new \DateTimeImmutable();
        $token = new ResetToken('Token', $now->modify('+1 day'));

        $user = $builder->buildSignedUpUser();

        $user->requestPasswordReset($token, $now);
        $this->expectExceptionMessage('Resetting is already requested.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $builder = new UserBuilder();
        $now = new \DateTimeImmutable();
        $user = $builder->buildSignedUpUser();

        $tokenFirst = new ResetToken('Token', $now->modify('+1 day'));

        $user->requestPasswordReset($tokenFirst, $now);

        $this->assertEquals($tokenFirst, $user->getResetToken());

        $tokenSecond = new ResetToken('TokenSecond', $now->modify('+3 day'));
        $user->requestPasswordReset($tokenSecond, $now->modify('+2 day'));

        $this->assertEquals($tokenSecond, $user->getResetToken());
    }
}