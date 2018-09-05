<?php

// tests/Repository/Capture/CaptureRepositoryTest.php

namespace App\Tests\Repository\Capture;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Bird;
use App\Services\Capture\NAOCaptureManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CaptureRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $elementsPerPage; 
    private $firstEntrance;
    private $user;
    private $naoCaptureManager;
    private $publishedStatus;
    private $validatedStatus;
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

        $this->elementsPerPage = 2;
        $this->firstEntrance = 0;
        $this->user = $this->entityManager->getRepository(User::class)->findOneById('3');
        $this->bird = $this->entityManager->getRepository(Bird::class)->findOneById('1');
        $this->region = 'Ile-de-France';
        $this->validatedStatus = 'validated';
		$this->waitingStatus = 'waiting_for_validation';
		$this->draftStatus = 'draft';
		$this->publishedStatus = 'published';
    }

    public function testGetPublishedCaptures()
    {
        $publishedCaptures = $this->entityManager
            ->getRepository(Capture::class)
            ->getPublishedCaptures($this->publishedStatus, $this->validatedStatus)
        ;

        $this->assertCount(3, $publishedCaptures);
    }

    public function testGetPublishedCapturesPerPage()
    {
        $publishedCaptures = $this->entityManager
            ->getRepository(Capture::class)
            ->getPublishedCapturesPerPage($this->elementsPerPage, $this->firstEntrance, $this->publishedStatus, $this->validatedStatus)
        ;

        $this->assertCount(2, $publishedCaptures);
    }

    public function testGetLastPublishedCaptures()
    {
        $lastPublishedCaptures = $this->entityManager
            ->getRepository(Capture::class)
            ->getLastPublishedCaptures($this->elementsPerPage, $this->publishedStatus, $this->validatedStatus)
        ;

        $this->assertCount(2, $lastPublishedCaptures);
    }

    public function testGetPublishedCapture()
    {
        $publishedCapture = $this->entityManager
            ->getRepository(Capture::class)->getPublishedCapture('1', $this->draftStatus, $this->waitingStatus);

        $this->assertEquals(1, $publishedCapture->getId());
    }

    public function testGetWaitingForValidationCaptures()
    {
        $waitingForValidationCaptures = $this->entityManager
            ->getRepository(Capture::class)->getCapturesByStatus($this->waitingStatus);

        $this->assertCount(2, $waitingForValidationCaptures); 
    }

    public function testGetWaitingForValidationCapturesPerPage()
    {
        $waitingForValidationCapturesPerPage = $this->entityManager
            ->getRepository(Capture::class)->getCapturesByStatusPerPage($this->draftStatus, $this->elementsPerPage, $this->firstEntrance);

        $this->assertCount(2, $waitingForValidationCapturesPerPage);
    }

    public function testGetBirdPublishedCaptures()
    {
        $birdPublishedCaptures = $this->entityManager
            ->getRepository(Capture::class)->getBirdPublishedCaptures('1', $this->draftStatus, $this->waitingStatus);

        $this->assertCount(2,  $birdPublishedCaptures);
    }

    public function testGetUserCapturesPerPage()
    {
        $userCapturesPerPage =  $this->entityManager
            ->getRepository(Capture::class)->getUserCapturesPerPage( $this->elementsPerPage, $this->firstEntrance, '1');

        $this->assertCount(1, $userCapturesPerPage);
    }

    public function testCountWaitingForValidationCaptures()
    {
        $numberOfWaitingForValidationCaptures = $this->entityManager
            ->getRepository(Capture::class)
            ->countByStatus($this->waitingStatus)
        ;

        $this->assertEquals(2, $numberOfWaitingForValidationCaptures);
    }

    public function testCountPublishedCaptures()
    {
        $numberOfPublishedCaptures = $this->entityManager->getRepository(Capture::class)->countPublishedCaptures($this->publishedStatus, $this->validatedStatus);

        $this->assertEquals(3, $numberOfPublishedCaptures);
    }

    public function testCountPublishedAuthorCaptures()
    {
        $numberOfAuthor3PublishedCaptures = $this->entityManager->getRepository(Capture::class)->countByStatusAndAuthor($this->publishedStatus, $this->user);

        $this->assertEquals(2, $numberOfAuthor3PublishedCaptures);
    }

    public function testCountAuthorCaptures()
    {
        $numberOfAuthor3Captures = $this->entityManager->getRepository(Capture::class)->countAuthorCaptures($this->user);

        $this->assertEquals(4, $numberOfAuthor3Captures);
    }

    public function testCountSearchPulishedCapturesByBirdAndRegion()
    {
        $nbCapturesBird1IDF = $this->entityManager->getRepository(Capture::class)->countSearchCapturesByBirdAndRegion($this->bird, $this->region, $this->draftStatus, $this->waitingStatus);

        $this->assertEquals(2, $nbCapturesBird1IDF);
    }

    public function testCountSearchPublishedCapturesByBird()
    {
        $nbCapturesBird1 = $this->entityManager->getRepository(Capture::class)->countSearchCapturesByBird($this->bird, $this->draftStatus, $this->waitingStatus);

        $this->assertEquals(2, $nbCapturesBird1);
    }

    public function testCountSearchCapturesByRegion()
    {
        $nbCapturesIDF = $this->entityManager->getRepository(Capture::class)->countSearchCapturesByRegion($this->region, $this->draftStatus, $this->waitingStatus);

        $this->assertEquals(3, $nbCapturesIDF);
    }

    public function testSearchCapturesByBirdAndRegionPerPage()
    {
        $capturesBird1IDF = $this->entityManager->getRepository(Capture::class)->searchCapturesByBirdAndRegionPerPage($this->bird, $this->region, $this->elementsPerPage, $this->firstEntrance, $this->draftStatus, $this->waitingStatus);

        $this->assertCount(2, $capturesBird1IDF);
    }

    public function testSearchCapturesByBirdPerPage()
    {
        $capturesBird1 = $this->entityManager->getRepository(Capture::class)->searchCapturesByBirdPerPage($this->bird, $this->elementsPerPage, $this->firstEntrance, $this->draftStatus, $this->waitingStatus);

        $this->assertCount(2, $capturesBird1);
    }

    public function testSearchCapturesByRegionPerPage()
    {
        $capturesIDF = $this->entityManager->getRepository(Capture::class)->searchCapturesByRegionPerPage($this->region, $this->elementsPerPage, $this->firstEntrance, $this->draftStatus, $this->waitingStatus);

        $this->assertCount(2, $capturesIDF);
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