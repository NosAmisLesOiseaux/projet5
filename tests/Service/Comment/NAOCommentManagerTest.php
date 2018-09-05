<?php

// tests/Service/Comment/NAOCommentManagerTest.php

namespace App\Tests\Service\Comment;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Comment;
use App\Services\Comment\NAOCommentManager;

class NAOCommentManagerTest extends WebTestCase
{
    private $naoCommentManager;
    private $elementsPerPage; 
    private $firstEntrance;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->elementsPerPage = 1;
        $this->firstEntrance = 0;

        $this->naoCommentManager = $kernel->getContainer()->get('app.nao_comment_manager');
        $this->page = '1';
    }

    public function testGetPublishedComments()
    {
        $publishedComments = $this->naoCommentManager->getPublishedComments();

        $this->assertCount(2, $publishedComments);
    }

    public function testGetPublishedCommentsPerPage()
    {
        $numberOfPublishedComments = '2';

        $publishedCommentsPerPage = $this->naoCommentManager->getPublishedCommentsPerPage($this->page, $numberOfPublishedComments, $this->elementsPerPage);

        $this->assertCount(1, $publishedCommentsPerPage);
    }

    public function testGetReportedComments()
    {
        $reportedComments = $this->naoCommentManager->getReportedComments();

        $this->assertCount(1, $reportedComments);
    }

    public function testGetReportedCommentsPerPage()
    {
        $numberOfReportedComments = '1';

        $reportedCommentsPerPage = $this->naoCommentManager->getReportedCommentsPerPage($this->page, $numberOfReportedComments, $this->elementsPerPage);

        $this->assertCount(1, $reportedCommentsPerPage);
    }


    public function testReportComment()
    {
        $comment = new Comment();
        $comment->setPublished(true);

        $this->naoCommentManager->reportComment($comment);

       $this->assertFalse($comment->getPublished()); 
    }

    public function testIgnoreReportedComment()
    {
        $comment = new Comment();
        $comment->setPublished(false);

        $this->naoCommentManager->ignoreReportedComment($comment);

       $this->assertTrue($comment->getPublished()); 
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