<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 02/08/18
 * Time: 16:35
 */

namespace App\Tests\Entity;


use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    public function setup()
    {
        $this->user = new User();
        $this->user->setActive(true);
        $this->user->setDateRegister(new \DateTime('now'));
        $this->user->setEmail("contact@nao.fr");
        $this->user->setFirstname("John");
        $this->user->setLastname("Doe");
        $this->user->setPassword("password");
        $this->user->setRoles(array("ROLE_USER"));
        $this->user->setUsername("john");
    }

    public function testUserIsInstanceOfUser()
    {
        $this->assertInstanceOf(User::class, $this->user);
    }

    public function testUserIsActive()
    {
        $this->assertTrue($this->user->getActive(), "L'utilisateur n'est pas actif");
    }

    public function testUserHasValidEmail()
    {
        $this->assertNotNull($this->user->getEmail(), "L'email de l'utilisateur n'existe pas.");
        $this->assertRegExp("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $this->user->getEmail(), "L'email est invalide.");
    }

    public function testUserHasFirstname()
    {
        $this->assertNotNull($this->user->getFirstname(), "Le prénom de l'utilisateur n'existe pas.");
    }

    public function testUserHasLastname()
    {
        $this->assertNotNull($this->user->getLastname(), "Le nom de l'utilisateur n'existe pas.");
    }

    public function testUserHasNaturalistRole()
    {
        $this->assertContains("ROLE_NATURALIST", $this->user->getRoles());
    }

    public function testUserHasJustParticularRole()
    {
        $this->assertContainsOnly("ROLE_USER", $this->user->getRoles());
    }
}
