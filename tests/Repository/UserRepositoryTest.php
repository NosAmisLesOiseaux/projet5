<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 05/09/18
 * Time: 20:17
 */

namespace App\Tests\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testLoadUserByUsername($username)
    {
        $user = $this->getRepository()->loadUserByUsername($username);
        $this->assertInstanceOf(User::class, $user, "User n'est pas une instance de User.");
    }

    public function testFindUserByEmail($email)
    {
        $user = $this->getRepository()->findUserByEmail($email);
        $this->assertInstanceOf(User::class, $user, "User n'est pas une instance de User.");
    }

    public function testFindByActivationCode($code)
    {
        $user = $this->getRepository()->findByActivationCode($code);
        $this->assertInstanceOf(User::class, $user, "User n'est pas une instance de User.");
    }

    public function testFindUserByToken($token)
    {
        $user = $this->getRepository()->findUserByToken($token);
        $this->assertInstanceOf(User::class, $user, "User n'est pas une instance de User.");
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository(User::class);
    }
}
