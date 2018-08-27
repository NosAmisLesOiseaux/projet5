<?php

// src/Services/NAOBirdManager.php

namespace App\Services\Bird;

use App\Services\NAOManager;
use App\Entity\Bird;
use App\Services\Capture\NAOCaptureManager;

class NAOCountBirds
{
	private $naoManager;
	private $waitingStatus;
	private $draftStatus;
	private $naoCaptureManager;

	public function __construct(NAOManager $naoManager, NAOCaptureManager $naoCaptureManager)
	{
		$this->naoManager = $naoManager;
		$this->naoCaptureManager = $naoCaptureManager;
		$this->waitingStatus = $this->naoCaptureManager->getWaitingStatus();
		$this->draftStatus = $this->naoCaptureManager->getDraftStatus();
	}

	public function countBirds()
	{
		return $numberOfBirds = $this->naoManager->getEm()->getRepository(Bird::class)->countBirds();
	}

	public function countBirdsByLetter($letter)
	{
		return $numberBirdsLetter = $this->naoManager->getEm()->getRepository(Bird::class)->countBirdsByLetter($letter);
	}

	public function countSearchBirdsByRegion($region)
	{
		return $numberSearchBirdsByRegion = $this->naoManager->getEm()->getRepository(Bird::class)->countSearchBirdsByRegion($region, $this->draftStatus, $this->waitingStatus);
	}

	public function countSearchBirdsByRegionAndDate($region, $date)
	{
		return $numberSearchBirdsByRegionAndDate = $this->naoManager->getEm()->getRepository(Bird::class)->countSearchBirdsByRegionAndDate($region, $this->draftStatus, $this->waitingStatus, $date);
	}

	public function countSearchBirdsByRegionAndLetter($region, $letter)
	{
		return $numberSearchBirdsByRegionAndLetter = $this->naoManager->getEm()->getRepository(Bird::class)->countSearchBirdsByRegionAndLetter($region, $this->draftStatus, $this->waitingStatus, $letter);
	}
}