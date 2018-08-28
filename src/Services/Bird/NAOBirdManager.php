<?php

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

    /**
     * @param $page
     * @param $numberOfBirds
     * @param $numberOfBirdsPerPage
     * @return mixed
     */
	public function getBirdsPerPage($page, $numberOfBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfBirds, $numberOfBirdsPerPage);

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->getBirdsPerPage($numberOfBirdsPerPage, $firstEntrance);
	}

    /**
     * @param $letter
     * @param $page
     * @param $numberOfBirds
     * @param $numberOfBirdsPerPage
     * @return mixed
     */
	public function getBirdsByLetter($letter, $page, $numberOfBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfBirds, $numberOfBirdsPerPage);

		return $birds = $this->naoManager->getEm()->getRepository(Bird::class)->getBirdsByFirstLetter($letter, $numberOfBirdsPerPage, $firstEntrance);
	}

    /**
     * @param $region
     * @param $pageNumber
     * @param $numberOfSearchBirds
     * @param $numberOfBirdsPerPage
     * @return mixed
     */
	public function searchBirdsByRegionPerPage($region, $pageNumber, $numberOfSearchBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($pageNumber, $numberOfSearchBirds, $numberOfBirdsPerPage);

		return $searchBirdsByRegionPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->searchBirdsByRegionPerPage($region, $numberOfBirdsPerPage, $firstEntrance, $this->draftStatus, $this->waitingStatus);
	}

    /**
     * @param $region
     * @param $date
     * @return mixed
     */
	public function searchBirdsByRegionAndDate($region, $date)
	{
		return $searchBirdsByRegion = $this->naoManager->getEm()->getRepository(Bird::class)->searchBirdsByRegionAndDate($region, $this->draftStatus, $this->waitingStatus, $date);
	}

    /**
     * @param $birdName
     * @return Bird $bird
     */
	public function getBirdByVernacularOrValidName($birdName)
	{
		$bird = $this->naoManager->getEm()->getRepository(Bird::class)->findOneByVernacularname($birdName);

		if (empty($bird))
		{
			return $bird = $this->naoManager->getEm()->getRepository(Bird::class)->findOneByValidname($birdName);
		}

		return $bird;
	}

	public function uploadBirdCsv(array $form_data)
    {
        $data = file_get_contents(utf8_decode($form_data['image']));
        $line = explode("\n", $data);
        for ($i=1;$i<count($line);$i++)
        {
            $values = explode(";", $line[$i]);
            $bird = new Bird();
            $bird->setBirdOrder($values[3]);
            $bird->setFamily($values[4]);
            $bird->setCdName($values[5]);
            $bird->setValidname($values[9]);
            $bird->setVernacularname($values[13]);
            $this->naoManager->getEm()->persist($bird);
        }
        $this->naoManager->getEm()->flush();
    }

	/**
     * @param $region
     * @param $letter
     * @param $pageNumber
     * @param $numberOfSearchBirds
     * @param $numberOfBirdsPerPage
     * @return mixed
     */
	public function searchBirdsByRegionAndLetterPerPage($region, $letter, $pageNumber, $numberOfSearchBirds, $numberOfBirdsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($pageNumber, $numberOfSearchBirds, $numberOfBirdsPerPage);

		if (empty($region))
		{
			return $searchBirdsByLetterPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->getBirdsByFirstLetter($letter, $numberOfBirdsPerPage, $firstEntrance);
		}

		if (empty($letter))
		{
			return $searchBirdsByRegionPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->searchBirdsByRegionPerPage($region, $numberOfBirdsPerPage, $firstEntrance, $this->draftStatus, $this->waitingStatus);
		}
		else
		{
			return $searchBirdsByRegionAndLetterPerPage = $this->naoManager->getEm()->getRepository(Bird::class)->searchBirdsByRegionAndLetterPerPage($region, $letter, $numberOfBirdsPerPage, $firstEntrance, $this->draftStatus, $this->waitingStatus);
		}
	}
}
