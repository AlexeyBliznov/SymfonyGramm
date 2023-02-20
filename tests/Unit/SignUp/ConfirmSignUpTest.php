<?php

declare(strict_types=1);

namespace App\Tests\Unit\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Tests\Unit\UserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmSignUpTest extends TestCase
{
    public function testSuccess(): void
    {
        $builder = new UserBuilder();
        $user = $builder->buildSignedUpUser();
        $user->confirmSignUp();

        $this->assertFalse($user->getStatus()->isWait());
        $this->assertTrue($user->getStatus()->isActive());

        $this->assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $builder = new UserBuilder();
        $user = $builder->buildSignedUpUser();

        $user->confirmSignUp();

        $this->expectExceptionMessage('User is already confirmed');

        $user->confirmSignUp();
    }
}