<?php

// tests/RepositoriesTests/Bird/BirdRepositoryTest.php
namespace App\Tests\RepositoriesTests\Capture;

use App\Entity\Bird;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BirdRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $elementsPerPage; 
    private $firstEntrance;
    private $region;
    private $naoCaptureManager; 
    private $draftStatus;
    private $waitingStatus;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->elementsPerPage = 15;
        $this->firstEntrance = 0;
        $this->region = 'Ile-de-France';
        $this->naoCaptureManager = $kernel->getContainer()->get('app.nao_capture_manager');
        $this->waitingStatus = $this->naoCaptureManager->getWaitingStatus();
        $this->draftStatus = $this->naoCaptureManager->getDraftStatus();
    }
    
    public function testGetBirdsPerPage()
    {
         $birdsPerPage = $this->entityManager->getRepository(Bird::class)->getBirdsPerPage($this->elementsPerPage, $this->firstEntrance);

        $this->assertCount(15, $birdsPerPage);
    }

    public function testCountBirds()
    {
        $numberOfBirds =  $this->entityManager->getRepository(Bird::class)->countBirds();

        $this->assertEquals(3091, $numberOfBirds);
    }

    public function testCountSearchBirdsByRegion()
    {
        $numberBirdsIDF = $this->entityManager->getRepository(Bird::class)->countSearchBirdsByRegion($this->region, $this->draftStatus, $this->waitingStatus);

        $this->assertEquals(2, $numberBirdsIDF);
    }

     public function testSearchBirdsByRegionPerPage()
    {
         $numberBirdsIDFPerPage = $this->entityManager->getRepository(Bird::class)->searchBirdsByRegionPerPage($this->region, $this->elementsPerPage, $this->firstEntrance, $this->draftStatus, $this->waitingStatus);

        $this->assertCount(2, $numberBirdsIDFPerPage);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}