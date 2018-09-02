<?php

// tests/ServicesTests/NAOManagerTest.php

namespace App\Tests\ServicesTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;
use App\Entity\Bird;
use App\Entity\User;
use App\Services\NAOManager;

class NAOManagerTest extends WebTestCase
{
	private $naoManager;
	private $capture;
	private $capture2;
	private $bird;
	private $user;
	/**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->capture = new Capture();
        $this->capture2 = $this->entityManager->getRepository(Capture::class)->findOneById('17');
        $this->naoManager = $kernel->getContainer()->get('app.nao_manager');
        $this->bird = $this->entityManager->getRepository(Bird::class)->findOneById('1');
        $this->user = $this->entityManager->getRepository(User::class)->findOneById('3');
    }

	public function testAddNaturalistCapture() 
	{
		$this->capture->setContent('Nouvelle observation lors de ma promenade en vallée de Chevreuse');
		$this->capture->setLatitude('48.70981');
		$this->capture->setLongitude('2.041563');
		$this->capture->setAddress('Chemin Jean');
		$this->capture->setZipcode('78460');
		$this->capture->setCity('Chevreuse');
		$this->capture->setRegion('Ile-de-France');
		$this->capture->setStatus('published');

		$this->capture->setBird($this->bird);
		$this->capture->setUser($this->user);

 		$this->naoManager->addOrModifyEntity($this->capture);

 		$capture = $this->entityManager->getRepository(Capture::class)->findOneById($this->capture);

		$this->assertSame(17, $capture->getId());
	}

	public function testModifyNaturalistCapture() 
	{
		$this->capture2->setAddress('Chemin Jean Racine');
		$this->naoManager->addOrModifyEntity($this->capture2);

		$capture = $this->entityManager->getRepository(Capture::class)->findOneById($this->capture2);

		$this->assertSame('Chemin Jean Racine', $capture->getAddress());		
	}

	public function testRemoveEntity()
	{
		$this->naoManager->removeEntity($this->capture2);

		$this->assertNull($this->capture->getId(), "L'observation n'a pas été supprimée.");
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