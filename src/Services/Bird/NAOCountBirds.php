<?php

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

    /**
     * @return mixed
     */
	public function countBirds()
	{
		return $numberOfBirds = $this->naoManager->getEm()->getRepository(Bird::class)->countBirds();
	}

    /**
     * @param $letter
     * @return mixed
     */
	public function countBirdsByLetter($letter)
	{
		return $numberBirdsLetter = $this->naoManager->getEm()->getRepository(Bird::class)->countBirdsByLetter($letter);
	}

    /**
     * @param $region
     * @return mixed
     */
	public function countSearchBirdsByRegion($region)
	{
		return $numberSearchBirdsByRegion = $this->naoManager->getEm()->getRepository(Bird::class)->countSearchBirdsByRegion($region, $this->draftStatus, $this->waitingStatus);
	}

    /**
     * @param $region
     * @param $date
     * @return mixed
     */
	public function countSearchBirdsByRegionAndDate($region, $date)
	{
		return $numberSearchBirdsByRegion = $this->naoManager->getEm()->getRepository(Bird::class)->countSearchBirdsByRegionAndDate($region, $this->draftStatus, $this->waitingStatus, $date);
	}

	/**
     * @param $region
     * @param $letter
     * @return mixed
     */
	public function countSearchBirdsByRegionAndLetter($region, $letter)
	{
		return $numberSearchBirdsByRegionAndLetter = $this->naoManager->getEm()->getRepository(Bird::class)->countSearchBirdsByRegionAndLetter($region, $this->draftStatus, $this->waitingStatus, $letter);
	}
}
