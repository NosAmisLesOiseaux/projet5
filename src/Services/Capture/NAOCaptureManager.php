<?php

// src/Services/Capture/NAOCaptureManager.php

namespace App\Services\Capture;

use App\Entity\User;
use App\Services\NAOManager;
use App\Entity\Capture;
use App\Services\NAOPagination;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class NAOCaptureManager
{
	private $naoPagination;
	private $naoManager;
	private $container;

	public function __construct(NAOPagination $naoPagination, NAOManager $naoManager, ContainerInterface $container)
	{
		$this->naoManager = $naoManager;
		$this->naoPagination = $naoPagination;
		$this->container = $container;
	}

    /**
     * @param Capture $capture
     * @param User $user
     * @return Capture
     */
	public function setStatusOnCapture(Capture $capture, User $user): Capture
    {
        if ($user->getAccountType() === 'particular') $capture->setStatus('waiting_for_validation');
        return $capture;
    }

    public function addOrModifyImage(UploadedFile $file, string $directory)
    {
        //$bird_image = $this->container->get('app.avatar_service')->buildImage($data['image'], $directory);
    }

	public function getPublishedCaptures()
	{
		return $publishedCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCaptures();
	}

	public function getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);

		return $publishedCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapturesPerPage($numberOfElementsPerPage, $firstEntrance);
	}

	public function getPublishedCapture($id)
	{
		return $publishedCapture = $this->naoManager->getEm()->getRepository(Capture::class)->getPublishedCapture($id);
	}

	public function getWaintingForValidationCaptures()
	{
		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatus('waiting for validation');
	}

	public function getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);

		return $waintingForValidationCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getCapturesByStatusPerPage('waiting for validation', $numberOfElementsPerPage, $firstEntrance);
	}

    /**
     * @param Capture $capture
     * @param User $naturalist
     */
	public function validateCapture(Capture $capture, User $naturalist)
	{
		$capture->setStatus('validated');
		$capture->setValidatedBy($naturalist);
		$naturalist->addValidatedCapture($capture);
		$this->naoManager->addOrModifyEntity($capture);
		$this->naoManager->addOrModifyEntity($naturalist);
	}

	public function setWaitingStatus(Capture $capture)
	{
		$capture->setStatus('waiting for validation');
	}

	public function getBirdPublishedCaptures($id)
	{
		return $birdCaptures = $this->naoManager->getEm()->getRepository(Capture::class)->getBirdPublishedCaptures($id);
	}

	public function getUserCapturesPerPage($page, $numberOfUserCaptures, $numberOfElementsPerPage, $id)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($page, $numberOfUserCaptures, $numberOfElementsPerPage);

		return $UserCapturesPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->getUserCapturesPerPage($numberOfElementsPerPage, $firstEntrance, $id);
	}

	public function searchCapturesByBirdAndRegionPerPage($bird, $region, $pageNumber, $numberOfSearchCaptures, $numberOfPublishedCapturesPerPage)
	{
		$firstEntrance = $this->naoPagination->getFirstEntrance($pageNumber, $numberOfSearchCaptures, $numberOfPublishedCapturesPerPage);

		if (empty($bird))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByRegionPerPage($region, $numberOfPublishedCapturesPerPage, $firstEntrance);
		}

		if (empty($region))
		{
			return $numberSearchCapturesByBirdAndRegion = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByBirdPerPage($bird, $numberOfPublishedCapturesPerPage, $firstEntrance);
		}
		else
		{
			return $searchCaptureByBirdAndRegionPerPage = $this->naoManager->getEm()->getRepository(Capture::class)->searchCapturesByBirdAndRegionPerPage($bird, $region, $numberOfPublishedCapturesPerPage, $firstEntrance);
		}
	}
}
