<?php

// src/Services/Capture/NAOCaptureManager.php

namespace App\Services\Capture;

use App\Entity\User;
use App\Services\NAOManager;
use App\Entity\Capture;
use App\Services\Pagination\NAOPagination;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NAOCaptureManager
{
	private $naoPagination;
	private $naoManager;
	private $container;
	private $validatedStatus;
	private $waitingStatus;
	private $draftStatus;
	private $publishedStatus;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager, ContainerInterface $container)
	{
		$this->naoManager = $naoManager;
		$this->naoPagination = $naoPagination;
		$this->container = $container;
		$this->validatedStatus = 'validated';
		$this->waitingStatus = 'waiting_for_validation';
		$this->draftStatus = 'draft';
		$this->publishedStatus = 'published';
	}

	public function getValidatedStatus()
	{
		return $this->validatedStatus;
	}

	public function getWaitingStatus()
	{
		return $this->waitingStatus;
	}

	public function getDraftStatus()
	{
		return $this->draftStatus;
	}

	public function getPublishedStatus()
	{
		return $this->publishedStatus;
	}

    /**
     * @param array $data
     * @param string $directory
     * @return Capture
     */
	public function buildCapture(array $data, string $directory): Capture
    {
        $bird_image = $this->container->get('app.avatar_service')->buildImage($data['image'], $directory);
        $capture = new Capture();
        $capture->setBird($data['bird']);
        $capture->setImage($bird_image);
        $capture->setContent($data['content']);
        $capture->setLatitude($data['latitude']);
        $capture->setLongitude($data['longitude']);
        $capture->setAddress($data['address']);
        $capture->setComplement($data['address']);
        $capture->setZipcode($data['zipcode']);
        $capture->setCity($data['address']);
        $capture->setRegion($data['region']);
        return $capture;
    }

	public function getPublishedCaptures()
	{
		return $publishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCaptures($this->publishedStatus, $this->validatedStatus);
	}

	public function getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapturesPerPage($numberOfElementsPerPage, $firstEntrance, $this->publishedStatus, $this->validatedStatus);
	}

	public function getPublishedCapture($id)
	{
		return $publishedCapture = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapture($id, $this->draftStatus, $this->waitingStatus);
	}

	public function getWaintingForValidationCaptures()
	{
		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatus($this->waitingStatus);
	}

	public function getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);

		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatusPerPage($this->waitingStatus, $numberOfElementsPerPage, $firstEntrance);
	}

	public function validateCapture(Capture $capture, $naturalist)
	{
		$capture->setStatus($this->validatedStatus);
		$capture->setValidatedBy($naturalist);
	}

	public function setWaitingStatus(Capture $capture)
	{
		$capture->setStatus($this->waitingStatus);
	}

	public function getBirdPublishedCaptures($id)
	{
		return $birdCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getBirdPublishedCaptures($id, $this->draftStatus, $this->waitingStatus);
	}

	public function getUserCapturesPerPage($page, $numberOfUserCaptures, $numberOfElementsPerPage, $id)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfUserCaptures, $numberOfElementsPerPage);

		return $UserCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getUserCapturesPerPage($numberOfElementsPerPage, $firstEntrance, $id);
	}

	public function getLastPublishedCaptures()
	{
		$numberElements = $this->naoPagination->getNbHomeCapturesPerPage();

		return $lastCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getLastPublishedCaptures($numberElements, $this->publishedStatus, $this->validatedStatus);
	}

	public function searchCapturesByBirdAndRegionPerPage($bird, $region, $pageNumber, $numberOfSearchCaptures, $numberOfPublishedCapturesPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($pageNumber, $numberOfSearchCaptures, $numberOfPublishedCapturesPerPage);

		if (empty($bird))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByRegionPerPage($region, $numberOfPublishedCapturesPerPage, $firstEntrance, $this->draftStatus, $this->waitingStatus);
		}

		if (empty($region))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByBirdPerPage($bird, $numberOfPublishedCapturesPerPage, $firstEntrance, $this->draftStatus, $this->waitingStatus);
		}
		else
		{
			return $searchCaptureByBirdAndRegionPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByBirdAndRegionPerPage($bird, $region, $numberOfPublishedCapturesPerPage, $firstEntrance, $this->draftStatus, $this->waitingStatus);
		}
	}
}