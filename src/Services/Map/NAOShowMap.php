<?php

// src/Services/Map/NAOShowMap.php

namespace App\Services\Map;

use App\Services\NAOManager;
use App\Entity\Capture;
use Symfony\Component\HttpFoundation\JsonResponse;

class NAOShowMap extends NAOManager
{
	public function formatPublishedCaptures($captures)
	{
        $formatted = [];
        foreach ($captures as $capture) {
            $formatted[] = [
                'id' => $capture->getId(),
                'bird' => $capture->getBird()->getVernacularname(),
                'birdValidName' => $capture->getBird()->getValidname(),
                'latitude' => $capture->getLatitude(),
                'longitude' => $capture->getLongitude(),
                'address' => $capture->getAddress(),
                'complement' => $capture->getComplement(),
                'zipcode' => $capture->getZipcode(),
                'city' => $capture->getCity(),
            ];
        }

        return new JsonResponse($formatted);
	}	

	public function formatCapture($id)
	{
		$capture = $this->em->getRepository(Capture::class)->findOneById($id);

        $formatted[] = [
            'id' => $capture->getId(),
            'latitude' => $capture->getLatitude(),
            'longitude' => $capture->getLongitude(),
        ];

        return new JsonResponse($formatted);
	}  
}