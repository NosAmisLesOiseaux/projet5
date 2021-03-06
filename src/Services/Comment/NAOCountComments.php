<?php

// src/Services/Comment/NAOCountComments.php

namespace App\Services\Comment;

use App\Services\NAOManager;
use App\Entity\Comment;
use App\Services\Comment\NAOCommentManager;

class NAOCountComments 
{	
	private $naoManager;
	private $publishedStatus;
	private $reportedStatus;
	private $naoCommentManager;

	public function __construct(NAOManager $naoManager, NAOCommentManager $naoCommentManager)
	{
		$this->naoManager = $naoManager;
		$this->naoCommentManager = $naoCommentManager;
		$this->publishedStatus = $this->naoCommentManager->getPublishedStatus();
		$this->reportedStatus = $this->naoCommentManager->getReportedStatus();
	}

    /**
     * @return mixed
     */
	public function countPublishedComments()
	{
		return $numberOfPublishedComments = $this->naoManager->getEm()->getRepository(Comment::class)->countPublishedOrReportedComments($this->publishedStatus);
	}

    /**
     * @return mixed
     */
	public function countReportedComments()
	{
		return $numberOfReportedComments = $this->naoManager->getEm()->getRepository(Comment::class)->countPublishedOrReportedComments($this->reportedStatus);
	}

    /**
     * @param $capture
     * @return mixed
     */
	public function countCapturePublishedComments($capture)
	{
		return $captureComments = $this->naoManager->getEm()->getRepository(Comment::class)->countCaptureCommentsByStatus($capture, $this->publishedStatus );
	}
}
