<?php

// src/Services/Bird/NAOBirdManager.php

namespace App\Services\Bird;

use App\Services\Pagination\NAOPagination;
use App\Services\NAOManager;
use App\Entity\Bird;
use App\Services\Capture\NAOCaptureManager;

class NAOBirdManager
{
	private $naoPagination;
	private $naoManager;
	private $waitingStatus;
	private $draftStatus;
	private $naoCaptureManager;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager)
	{
		$this->naoPagination = $naoPagination;
		$this->naoManager = $naoManager;
		$this->naoCaptureManager = $naoCaptureManager;
		$this->waitingStatus = $this->naoCaptureManager->getWaitingStatus();
		$this->draftStatus = $this->naoCaptureManager->getDraftStatus();
	}

	public function getBirdsPerPage($page, $numberOfBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfBirds, $numberOfBirdsPerPage);

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->getBirdsPerPage($numberOfBirdsPerPage, $firstEntrance);
	}

	public function getBirdsByLetter($letter, $page, $numberOfBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfBirds, $numberOfBirdsPerPage);

		return $birds = $this->naoManager->getEm()->getRepository(Bird::class)->getBirdsByFirstLetter($letter, $numberOfBirdsPerPage, $firstEntrance);
	}

	public function searchBirdsByRegionPerPage($region, $pageNumber, $numberOfSearchBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($pageNumber, $numberOfSearchBirds, $numberOfBirdsPerPage);

		return $searchBirdsByRegionPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->searchBirdsByRegionPerPage($region, $numberOfBirdsPerPage, $firstEntrance, $this->draftStatus, $this->waitingStatus);
	}

	public function searchBirdsByRegionAndDate($region, $date)
	{
		return $searchBirdsByRegion = $this->naoManager->getEm()->getRepository(Bird::class)->searchBirdsByRegionAndDate($region, $this->draftStatus, $this->waitingStatus, $date);
	}

	public function getBirdByVernacularOrValidName($birdName)
	{
		$bird = $this->naoManager->getEm()->getRepository(Bird::class)->findOneByVernacularname($birdName);

		if (empty($bird))
		{
			return $bird = $this->naoManager->getEm()->getRepository(Bird::class)->findOneByValidname($birdName);
		}

		return $bird;
	}
}
