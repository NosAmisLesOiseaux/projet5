<?php

// src/Services/Capture/NAOCountCaptures.php

namespace App\Services\Capture;

use App\Services\NAOManager;
use App\Entity\Capture;
use App\Services\Capture\NAOCaptureManager;

class NAOCountCaptures
{	
	private $naoManager;
	private $validatedStatus;
	private $waitingStatus;
	private $draftStatus;
	private $publishedStatus;
	private $naoCaptureManager;

	public function __construct(NAOManager $naoManager, NAOCaptureManager $naoCaptureManager)
	{
		$this->naoManager = $naoManager;
		$this->naoCaptureManager = $naoCaptureManager;
		$this->validatedStatus = $this->naoCaptureManager->getValidatedStatus();
		$this->waitingStatus = $this->naoCaptureManager->getWaitingStatus();
		$this->draftStatus = $this->naoCaptureManager->getDraftStatus();
		$this->publishedStatus = $this->naoCaptureManager->getPublishedStatus();
	}

	public function countPublishedCaptures()
	{
		return $numberOfPublishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countPublishedCaptures($this->publishedStatus, $this->validatedStatus);
	}

	public function countPublishedCapturesByYear($date)
	{
		return $numberOfPublishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countPublishedCapturesByYear($this->publishedStatus, $this->validatedStatus, $date);
	}

	public function countDraftsCaptures()
	{
		return $numberOfPublishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countByStatus($this->draftStatus);
	} 

	public function countWaitingForValidationCaptures()
	{
		return $numberOfWaitingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countByStatus($this->waitingStatus);
	}

	public function countAuthorPublishedCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countByStatusAndAuthor($this->publishedStatus, $author);
	}

	public function countAuthorDraftsCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->em->getRepository(Capture::class)->countByStatusAndAuthor($this->draftStatus, $author);
	}

	public function countAuthorWaintingForValidationCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countByStatusAndAuthor($this->waitingStatus, $author);
	}

	public function countAuthorValidatedCaptures($author)
	{
		return $numberOfPublishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countByStatusAndAuthor($this->validatedStatus, $author);
	}

	public function countUserCaptures($id)
	{
		return $numberOfCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->countAuthorCaptures($id);
	}

	public function countSearchCapturesByBirdAndRegion($bird, $region)
	{
		if (empty($bird))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->countSearchCapturesByRegion($region, $this->draftStatus, $this->waitingStatus);
		}

		if (empty($region))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->countSearchCapturesByBird($bird, $this->draftStatus, $this->waitingStatus);
		}
		else
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->countSearchCapturesByBirdAndRegion($bird, $region, $this->draftStatus, $this->waitingStatus);
		}
	}
}