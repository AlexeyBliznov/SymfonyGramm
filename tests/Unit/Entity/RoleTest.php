<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Model\User\Entity\User\Role;
use App\Tests\Unit\UserBuilder;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $builder = new UserBuilder();
        $user = $builder->buildSignedUpUser();

        $user->changeRole(Role::admin());
        $this->assertTrue($user->getRole()->isAdmin());
        $this->assertFalse($user->getRole()->isUser());
    }

    public function testAlready():void
    {
        $builder = new UserBuilder();
        $user = $builder->buildSignedUpUser();

        $this->expectExceptionMessage('Role is already same.');
        $user->changeRole(Role::user());
    }
}