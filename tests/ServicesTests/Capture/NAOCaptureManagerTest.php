<?php

// tests/ServicesTests/Capture/NAOCaptureManagerTest.php

namespace App\Tests\ServicesTests\Capture;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Bird;
use App\Services\Capture\NAOCaptureManager;

class NAOCaptureManagerTest extends WebTestCase
{
    private $elementsPerPage; 
    private $firstEntrance;
    private $page;
    private $user;
    private $capture;
    private $naoCaptureManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->elementsPerPage = 2;
        $this->firstEntrance = 0;
        $this->user = $this->entityManager->getRepository(User::class)->findOneById('3');
        $this->bird = $this->entityManager->getRepository(Bird::class)->findOneById('1');
        $this->capture = $this->entityManager->getRepository(Capture::class)->findOneById('1');
        $this->region = 'Ile-de-France';
        $this->naoCaptureManager = $kernel->getContainer()->get('app.nao_capture_manager');
        $this->page = '1';
    }

    public function testGetPublishedCaptures()
    {
        $publishedCaptures = $this->naoCaptureManager->getPublishedCaptures();

        $this->assertCount(3, $publishedCaptures);
    }

    public function testGetPublishedCapturesPerPage()
    {
        $numberOfPublishedCaptures = '3';

        $publishedCapturesPerPage = $this->naoCaptureManager->getPublishedCapturesPerPage($this->page, $numberOfPublishedCaptures, $this->elementsPerPage);

        $this->assertCount(2, $publishedCapturesPerPage);
    }

    public function testGetPublishedCapture()
    {
        $publishedCapture = $this->naoCaptureManager->getPublishedCapture($this->capture);

        $this->assertEquals(1, $publishedCapture->getId());
    }

    public function testGetWaintingForValidationCaptures()
    {
        $waitingForValidationCaptures = $this->naoCaptureManager->getWaintingForValidationCaptures();

        $this->assertCount(2, $waitingForValidationCaptures); 
    }

    public function testGetWaintingForValidationCapturesPerPage()
    {
        $numberOfWaitingForValidationCaptures = '2';

        $waitingForValidationCapturesPerPage = $this->naoCaptureManager->getWaintingForValidationCapturesPerPage($this->page, $numberOfWaitingForValidationCaptures, $this->elementsPerPage);

        $this->assertCount(2, $waitingForValidationCapturesPerPage); 
    }

    public function testValidateCapture()
    {
        $capture = new Capture();
        $capture->setStatus('waiting for validation');

        $this->naoCaptureManager->validateCapture($capture, $this->user);

        $this->assertSame('validated', $capture->getStatus()); 
    }

    public function testSetWaitingStatus()
    {
        $this->naoCaptureManager->setWaitingStatus($this->capture);

        $this->assertSame('waiting_for_validation', $this->capture->getStatus());
    }

    public function testGetBirdPublishedCaptures()
    {
        $birdPublishedCaptures = $this->naoCaptureManager->getBirdPublishedCaptures($this->capture);

        $this->assertCount(2,  $birdPublishedCaptures);
    }

    public function testGetUserCapturesPerPage()
    {
        $numberOfUserCaptures = '4';

        $userCapturesPerPage = $this->naoCaptureManager->getUserCapturesPerPage($this->page, $numberOfUserCaptures, $this->elementsPerPage, $this->user);

        $this->assertCount(2, $userCapturesPerPage);
    }

    public function testSearchCapturesByBirdAndRegionPerPage()
    {
        $numberOfSearchCaptures = '2';
        $capturesBird1IDF = $this->naoCaptureManager->searchCapturesByBirdAndRegionPerPage($this->bird, $this->region, $this->page, $numberOfSearchCaptures, $this->elementsPerPage);

        $this->assertCount(2, $capturesBird1IDF);
    }

    public function testSearchCapturesByBirdPerPage()
    {
        $numberOfSearchCaptures = '2';
        $region = '';

        $capturesBird1 = $this->naoCaptureManager->searchCapturesByBirdAndRegionPerPage($this->bird, $region, $this->page, $numberOfSearchCaptures, $this->elementsPerPage);

        $this->assertCount(2, $capturesBird1);
    }

    public function testSearchCapturesByRegionPerPage()
    {
        $numberOfSearchCaptures = '2';
        $bird = '';

        $capturesIDF = $this->naoCaptureManager->searchCapturesByBirdAndRegionPerPage($bird, $this->region, $this->page, $numberOfSearchCaptures, $this->elementsPerPage);

        $this->assertCount(2, $capturesIDF);
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