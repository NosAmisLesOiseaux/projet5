<?php

// tests/RepositoriesTests/Comment/CommentRepositoryTest.php
namespace App\Tests\RepositoriesTests\Comment;

use App\Entity\Comment;
use App\Entity\Capture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $elementsPerPage; 
    private $firstEntrance;
    private $catpure;

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
        $this->capture = $this->entityManager->getRepository(Capture::class)->findOneById('1');
    }

    public function getPublishedCommentsPerPage()
    {
        $publishedComments = $this->entityManager->getRepository(Comment::class)->getCommentsByStatusPerPage(true, $this->elementsPerPage, $this->firstEntrance);

        $this->assertCount(2, $publishedComments);
    }

    public function getReportedCommentsPerPage()
    {
        $reportedComments = $this->entityManager->getRepository(Comment::class)->getCommentsByStatusPerPage(false, $this->elementsPerPage, $this->firstEntrance);

        $this->assertCount(1, $reportedComments);
    }

    public function testCountPublishedComments()
    {
        $numberOfPublishedComments = $this->entityManager
            ->getRepository(Comment::class)
            ->countPublishedOrReportedComments(true)
        ;

        $this->assertEquals(2, $numberOfPublishedComments);
    }

    public function testCountReportedComments()
    {
        $numberOfReportedComments = $this->entityManager
            ->getRepository(Comment::class)
            ->countPublishedOrReportedComments(false)
        ;

        $this->assertEquals(1, $numberOfReportedComments);
    }

    public function testCountCaptureComments()
    {
        $capture1Comments = $this->entityManager->getRepository(Comment::class)->countCaptureComments($this->capture);

        $this->assertEquals(2, $capture1Comments);
    }

    public function testCountCapturePublishedComments()
    {
        $capture1PublishedComments = $this->entityManager->getRepository(Comment::class)->countCaptureCommentsByStatus($this->capture, true);

        $this->assertEquals(2, $capture1PublishedComments);
    }

    public function testCountCaptureReportedComments()
    {
        $capture1ReportedComments = $this->entityManager->getRepository(Comment::class)->countCaptureCommentsByStatus($this->capture, false);

        $this->assertEquals(0, $capture1ReportedComments);
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