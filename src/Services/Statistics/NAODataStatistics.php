<?php

// src/Services/Statistics/DataStatistics.php

namespace App\Services\Statistics;

use App\Services\Capture\NAOCountCaptures;
use App\Services\Bird\NAOCountBirds;
use App\Services\Bird\NAOBirdManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class NAODataStatistics
{
    private $naoCountCaptures;
    private $naoCountBirds;
    private $naoBirdManager;

    public function __construct(NAOCountCaptures $naoCountCaptures, NAOCountBirds $naoCountBirds, NAOBirdManager $naoBirdManager)
    {
        $this->naoCountCaptures = $naoCountCaptures;
        $this->naoCountBirds = $naoCountBirds;
        $this->naoBirdManager = $naoBirdManager;
    }

    public function getYears()
    {
        $years = [];
        for ($i = 2018; $i <= date('Y'); $i++)
        {
            $years[] += $i;
        }
        
        return $years;
    }

	public function formatBirdsByRegions($regions, $year)
	{
        $numberOfPublishedCaptures = $this->naoCountCaptures->countPublishedCapturesByYear($year);

        $regionsData = [];
        foreach ($regions as $region)
        {
            $regionName = $region['nom'];
            $numberOfBirds = $this->naoCountBirds->countSearchBirdsByRegionAndDate($regionName, $year);
            $birds = $this->naoBirdManager->searchBirdsByregionAndDate($regionName, $year);

            $birdsName = [];
            foreach ($birds as $bird) {
            if ($bird->getVernacularname() != null)
            {
                $birdsName[] = $bird->getVernacularname().' - '.$bird->getValidname();
                }
                else 
                {
                    $birdsName[] = $bird->getValidname();
                }
            }

            if ($numberOfBirds >= 1)
            {
                $regionsData[] = [
                    'region' => $regionName,
                    'numberOfBirds' => $numberOfBirds,
                    'birds' => $birdsName
                ];
        
                $formatted[] = [
                    'year' => $year,
                    'numberOfCaptures' => $numberOfPublishedCaptures,
                    'regions' => $regionsData,
                ];
            }
        }

        return new JsonResponse($formatted);
	}
}