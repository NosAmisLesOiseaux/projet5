<?php

// tests/ServicesTests/Capture/NAOCountCapturesTest.php

namespace App\Tests\Services\Capture;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Bird;
use App\Services\Capture\NAOCountCaptures;

class NAOCountCapturesTest extends WebTestCase
{
    private $bird;
    private $user;
    private $region;
    private $naoCountCaptures;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->user = $this->entityManager->getRepository(User::class)->findOneById('3');
        $this->user2 = $this->entityManager->getRepository(User::class)->findOneById('1');
        $this->bird = $this->entityManager->getRepository(Bird::class)->findOneById('1');
        $this->region = 'Ile-de-France';
        $this->naoCountCaptures = $kernel->getContainer()->get('app.nao_count_captures');
    }

    public function testCountPublishedCaptures()
    {
        $numberOfPublishedCaptures = $this->naoCountCaptures->countPublishedCaptures();

        $this->assertEquals(3, $numberOfPublishedCaptures);
    }

    public function testCountDraftsCaptures()
    {
        $numberOfDraftsCaptures = $this->naoCountCaptures->countDraftsCaptures();

        $this->assertEquals(2, $numberOfDraftsCaptures);
    }

    public function countWaitingForValidationCaptures()
    {
        $numberOfWaitingForValidationCaptures = $this->naoCountCaptures->countWaitingForValidationCaptures();

        $this->assertEquals(2, $numberOfWaitingForValidationCaptures);
    }

    public function testCountAuthorPublishedCaptures()
    {
        $numberOfAuthor3PublishedCaptures = $this->naoCountCaptures->countAuthorPublishedCaptures($this->user);

        $this->assertEquals(2, $numberOfAuthor3PublishedCaptures);
    }

    public function testCountAuthorDraftsCaptures()
    {
        $numberOfAuthor3DraftsCaptures = $this->naoCountCaptures->countAuthorDraftsCaptures($this->user);

        $this->assertEquals(2, $numberOfAuthor3DraftsCaptures);       
    }

    public function testCountAuthorWaintingForValidationCaptures()
    {
        $numberOfAuthor1WaintingForValidationCaptures = $this->naoCountCaptures->countAuthorWaintingForValidationCaptures($this->user2);

        $this->assertEquals(1, $numberOfAuthor1WaintingForValidationCaptures); 
    }

    public function testCountAuthorValidatedCaptures()
    {
        $numberOfAuthor1ValidatedCaptures = $this->naoCountCaptures->countAuthorValidatedCaptures($this->user2);

        $this->assertEquals(0, $numberOfAuthor1ValidatedCaptures); 
    }

    public function testCountUserCaptures()
    {
        $numberOfAuthor3Captures = $this->naoCountCaptures->countUserCaptures($this->user);

        $this->assertEquals(4, $numberOfAuthor3Captures);
    }

    public function testCountSearchCapturesByBirdAndRegion()
    {
        $nbCapturesBird1IDF = $this->naoCountCaptures->countSearchCapturesByBirdAndRegion($this->bird, $this->region);

        $this->assertEquals(2, $nbCapturesBird1IDF);
    }

    public function testCountSearchCapturesByBird()
    {
        $region = '';
        $nbCapturesBird1 = $this->naoCountCaptures->countSearchCapturesByBirdAndRegion($this->bird, $region);

        $this->assertEquals(2, $nbCapturesBird1);
    }

    public function testCountSearchCapturesByRegion()
    {
        $bird = '';
        $nbCapturesIDF = $this->naoCountCaptures->countSearchCapturesByBirdAndRegion($bird, $this->region);

        $this->assertEquals(3, $nbCapturesIDF);
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