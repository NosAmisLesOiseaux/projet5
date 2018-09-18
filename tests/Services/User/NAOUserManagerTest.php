<?php

// tests/Services/User/NAOUserManagerTest.php

namespace App\Tests\Services\User;

use App\Services\NAOManager;
use App\Entity\User;
use App\Services\User\NAOUserManager;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NAOUserManagerTest extends WebTestCase
{
    private $naoUserManager;
    private $particular;
    private $naturalist;
    private $administrator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->particular = $this->entityManager->getRepository(User::class)->findOneById('1');
        $this->naturalist = $this->entityManager->getRepository(User::class)->findOneById('3');
        $this->administrator = $this->entityManager->getRepository(User::class)->findOneById('4');
        $this->naoUserManager = $kernel->getContainer()->get('app.nao_user_manager');
    }

	public function testGetParticularRoleFR()
    {
        $particularRoleFR = $this->naoUserManager->getRoleFR($this->particular);

        $this->assertSame('Particulier', $particularRoleFR);
    }

    public function testGetNaturalistRoleFR()
    {
        $naturalistRoleFR = $this->naoUserManager->getRoleFR($this->naturalist);

        $this->assertSame('Naturaliste', $naturalistRoleFR);
    }

    public function testGetAdministratorRoleFR()
    {
        $administratorRoleFR = $this->naoUserManager->getRoleFR($this->administrator);

        $this->assertSame('Administrateur', $administratorRoleFR);
    }

    public function testParticularHasParicularRole()
    {
        $particularRole = $this->naoUserManager->getNaturalistOrParicularRole($this->particular);

        $this->assertSame('particular', $particularRole);
    }

    public function testNaturalistHasNaturalistRole()
    {
        $naturalistRole = $this->naoUserManager->getNaturalistOrParicularRole($this->naturalist);

        $this->assertSame('naturalist', $naturalistRole);
    }

    public function testAdministratorHasNaturalistRole()
    {
        $administratorRole = $this->naoUserManager->getNaturalistOrParicularRole($this->administrator);

        $this->assertSame('naturalist', $administratorRole);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; 
    }
}