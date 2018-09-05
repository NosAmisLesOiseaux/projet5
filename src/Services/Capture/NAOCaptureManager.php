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

    /**
     * @return string
     */
	public function getValidatedStatus()
	{
		return $this->validatedStatus;
	}

    /**
     * @return string
     */
	public function getWaitingStatus()
	{
		return $this->waitingStatus;
	}

    /**
     * @return string
     */
	public function getDraftStatus()
	{
		return $this->draftStatus;
	}

    /**
     * @return string
     */
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
        $bird_image = $this->container->get('app.image_manager')->buildImage($data['image'], $directory);
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

    /**
     * @param Capture $capture
     * @param User $user
     * @param string $role
     * @return Capture
     */
    public function setStatusOnCapture(Capture $capture, User $user, string $role)
    {
        if ($role === "particular") {
            $this->setWaitingStatus($capture);
        }
        $capture->setUser($user);
        return $capture;
    }

    /**
     * @return mixed
     */
	public function getPublishedCaptures()
	{
		return $publishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCaptures($this->publishedStatus, $this->validatedStatus);
	}

    /**
     * @param $page
     * @param $numberOfPublishedCaptures
     * @param $numberOfElementsPerPage
     * @return mixed
     */
	public function getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapturesPerPage($numberOfElementsPerPage, $firstEntrance, $this->publishedStatus, $this->validatedStatus);
	}

    /**
     * @param $id
     * @return mixed
     */
	public function getPublishedCapture($id)
	{
		return $publishedCapture = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapture($id, $this->draftStatus, $this->waitingStatus);
	}

    /**
     * @return mixed
     */
	public function getWaintingForValidationCaptures()
	{
		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatus($this->waitingStatus);
	}

    /**
     * @param $page
     * @param $numberOfWaitingForValidationCaptures
     * @param $numberOfElementsPerPage
     * @return mixed
     */
	public function getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);

		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatusPerPage($this->waitingStatus, $numberOfElementsPerPage, $firstEntrance);
	}

    /**
     * @param Capture $capture
     * @param $naturalist
     */
	public function validateCapture(Capture $capture, $naturalist)
	{
		$capture->setStatus($this->validatedStatus);
		$capture->setValidatedBy($naturalist);
	}

    /**
     * @param Capture $capture
     */
	public function setWaitingStatus(Capture $capture)
	{
		$capture->setStatus($this->waitingStatus);
	}

    /**
     * @param $id
     * @return mixed
     */
	public function getBirdPublishedCaptures($id)
	{
		return $birdCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getBirdPublishedCaptures($id, $this->draftStatus, $this->waitingStatus);
	}

    /**
     * @param $page
     * @param $numberOfUserCaptures
     * @param $numberOfElementsPerPage
     * @param $id
     * @return mixed
     */
	public function getUserCapturesPerPage($page, $numberOfUserCaptures, $numberOfElementsPerPage, $id)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfUserCaptures, $numberOfElementsPerPage);

		return $UserCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getUserCapturesPerPage($numberOfElementsPerPage, $firstEntrance, $id);
	}

    /**
     * @return mixed
     */
	public function getLastPublishedCaptures()
	{
		$numberElements = $this->naoPagination->getNbHomeCapturesPerPage();

		return $lastCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getLastPublishedCaptures($numberElements, $this->publishedStatus, $this->validatedStatus);
	}

    /**
     * @param $bird
     * @param $region
     * @param $pageNumber
     * @param $numberOfSearchCaptures
     * @param $numberOfPublishedCapturesPerPage
     * @return mixed
     */
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
