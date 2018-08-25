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

    /**
     * @return bool
     */
	public function getPublishedStatus()
	{
		return $this->publishedStatus;
	}

    /**
     * @return bool
     */
	public function getReportedStatus()
	{
		return $this->reportedStatus;
	}

    /**
     * @return mixed
     */
	public function getPublishedComments()
	{
		return $publishedComments = $this->naoManager->getEm()->getRepository(Comment::class)->findByPublished($this->publishedStatus);
	}

    /**
     * @param $page
     * @param $numberOfPublishedComments
     * @param $nbElementsPerPage
     * @return mixed
     */
	public function getPublishedCommentsPerPage($page, $numberOfPublishedComments, $nbElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedComments, $nbElementsPerPage);

		return $publishedCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCommentsByStatusPerPage($this->publishedStatus, $nbElementsPerPage, $firstEntrance);
	}

    /**
     * @return mixed
     */
	public function getReportedComments()
	{
		return $reportedComments = $this->naoManager->getEm()->getRepository(Comment::class)->findByPublished($this->reportedStatus);
	}

    /**
     * @param $page
     * @param $numberOfReportedComments
     * @param $nbElementsPerPage
     * @return mixed
     */
	public function getReportedCommentsPerPage($page, $numberOfReportedComments, $nbElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfReportedComments, $nbElementsPerPage);

		return $reportedCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCommentsByStatusPerPage($this->reportedStatus, $nbElementsPerPage, $firstEntrance);
	}

    /**
     * @param $id
     * @return mixed
     */
	public function getCapturePublishedComments($id)
    {
       return $captureCommentsPerPage = $this->naoManager->getEm()->getRepository(Comment::class)->getCapturePublishedComments($this->publishedStatus, $id);
    }

    /**
     * @param Comment $comment
     */
	public function reportComment(Comment $comment)
	{
		$comment->setPublished($this->reportedStatus);
	}

    /**
     * @param Comment $comment
     */
	public function ignoreReportedComment(Comment $comment)
	{
		$comment->setPublished($this->publishedStatus);
	}
}
