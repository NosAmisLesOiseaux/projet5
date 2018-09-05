<?php

namespace App\Tests\Entity;


use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    public function setup()
    {
        $this->user = new User();
    }

    public function testUserIsInstanceOfUser()
    {
        $this->assertInstanceOf(User::class, $this->user, "User is not an instance of User.");
    }
}
