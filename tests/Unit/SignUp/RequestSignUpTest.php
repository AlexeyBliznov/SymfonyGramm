<?php

declare(strict_types=1);

namespace App\Tests\Unit\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class RequestSignUpTest extends TestCase
{
    public function testPositive(): void
    {
        $user = new User();
        $user->create(
            new Email($email = 'Email@email.com'),
            $password = '1234',
            $token = 'token'
        );
        $this->assertTrue($user->getStatus()->isWait());
        $this->assertFalse($user->getStatus()->isActive());

        $this->assertEquals(mb_strtolower($email), $user->getEmail()->getValue());
        $this->assertEquals($password, $user->getPasswordHash());
        $this->assertEquals($token, $user->getConfirmToken());

        $this->assertTrue($user->getRole()->isUser());
    }
}
