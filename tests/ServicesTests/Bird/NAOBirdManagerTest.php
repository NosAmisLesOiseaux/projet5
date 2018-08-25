<?php

// tests/ServicesTests/Bird/NAOBirdManagerTest.php

namespace App\Tests\ServicesTests\Bird;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Bird;
use App\Services\Capture\NAOBirdManager;

class NAOBirdManagerTest extends WebTestCase
{
   private $numberOfBirdsPerPage;
    private $page;
    private $bird;
    private $naoBirdManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->numberOfBirdsPerPage = '15';
        $this->numberOfBirds = '3091';
        $this->bird = $this->entityManager->getRepository(Bird::class)->findOneById('1');
        $this->region = 'Ile-de-France';
        $this->naoBirdManager = $kernel->getContainer()->get('app.nao_bird_manager');
        $this->page = '1';
    }

    public function testGetBirdsPerPage()
    {
        $birdsPerPage = $this->naoBirdManager->getBirdsPerPage($this->page, $this->numberOfBirds, $this->numberOfBirdsPerPage);

        $this->assertCount(15, $birdsPerPage);
    }

    public function testSearchBirdsByRegionPerPage()
    {
        $numberOfBirdsIDF = '2';
        $numberBirdsIDFPerPage =  $this->naoBirdManager->searchBirdsByRegionPerPage($this->region, $this->page, $numberOfBirdsIDF, $this->numberOfBirdsPerPage);

        $this->assertCount(2, $numberBirdsIDFPerPage);
    }

    public function testGetBirdByVernacularname()
    {
        $birdName = $this->bird->getVernacularname();
        $bird = $this->naoBirdManager->getBirdByVernacularOrValidName($birdName);

        $this->assertEquals('1', $bird->getId());
    }

    public function testGetBirdByValidname()
    {
        $birdName = $this->bird->getValidname();
        $bird = $this->naoBirdManager->getBirdByVernacularOrValidName($birdName);

        $this->assertEquals('1', $bird->getId());
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