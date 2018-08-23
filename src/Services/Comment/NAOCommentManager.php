<?php

// src/Services/Comment/NAOCommentManager.php

namespace App\Services\Comment;

use App\Services\NAOManager;
use App\Services\Pagination\NAOPagination;
use App\Services\Pagination\sNAOPagination;
use App\Entity\Comment;

class NAOCommentManager 
{
	private $naoPagination;
	private $naoManager;
	private $publishedStatus;
	private $reportedStatus;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager)
	{
		$this->naoManager = $naoManager;
		$this->naoPagination = $naoPagination;
		$this->publishedStatus = true;
		$this->reportedStatus = false;
	}

	public function getPublishedStatus()
	{
		return $this->publishedStatus;
	}

	public function getReportedStatus()
	{
		return $this->reportedStatus;
	}

	public function getPublishedComments()
	{
		return $publishedComments = $this->naoManager->getEm()->getRepository(Comment::class)->findByPublished($this->publishedStatus);
	}

	public function getPublishedCommentsPerPage($page, $numberOfPublishedComments, $nbElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedComments, $nbElementsPerPage);

		return $publishedCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCommentsByStatusPerPage($this->publishedStatus, $nbElementsPerPage, $firstEntrance);
	}

	public function getReportedComments()
	{
		return $reportedComments = $this->naoManager->getEm()->getRepository(Comment::class)->findByPublished($this->reportedStatus);
	}

	public function getReportedCommentsPerPage($page, $numberOfReportedComments, $nbElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfReportedComments, $nbElementsPerPage);

		return $reportedCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCommentsByStatusPerPage($this->reportedStatus, $nbElementsPerPage, $firstEntrance);
	}

	public function getCapturePublishedComments($id)
    {
       return $captureCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCapturePublishedComments($this->publishedStatus, $id);
    }

	public function reportComment(Comment $comment)
	{
		$comment->setPublished($this->reportedStatus);
	}

	public function ignoreReportedComment(Comment $comment)
	{
		$comment->setPublished($this->publishedStatus);
	}
}