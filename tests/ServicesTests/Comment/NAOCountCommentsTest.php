<?php

// tests/ServicesTests/Comment/NAOCountCommentsTest.php

namespace App\Tests\ServicesTests\Comment;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Comment;
use App\Entity\Capture;
use App\Services\Capture\NAOCountCaptures;

class NAOCountCapturesTest extends WebTestCase
{
    private $capture;
    private $naoCountComments;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->capture = $this->entityManager->getRepository(Capture::class)->findOneById('1');
        $this->naoCountComments = $kernel->getContainer()->get('app.nao_count_comments');
    }

    public function testCountPublishedComments()
    {
        $numberOfPublishedComments = $this->naoCountComments->countPublishedComments();

        $this->assertEquals(2, $numberOfPublishedComments);
    }

    public function testCountReportedComments()
    {
        $numberOfReportedComments = $this->naoCountComments->countReportedComments();

        $this->assertEquals(1, $numberOfReportedComments);
    }

    public function testCountCapturePublishedComments()
    {
        $capture1Comments = $this->naoCountComments->countCapturePublishedComments($this->capture);

        $this->assertEquals(2, $capture1Comments);
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