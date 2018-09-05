<?php

// src/Services/Statistics/DataStatistics.php

namespace App\Services\Statistics;

use App\Services\Capture\NAOCountCaptures;
use App\Services\Bird\NAOCountBirds;
use App\Services\Bird\NAOBirdManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class NAODataStatistics
{
    private $naoCounCaptures;
    private $naoCountBirds;
    private $naoBirdManager;
    private $regions;

    public function __construct(NAOCountCaptures $naoCountCaptures, NAOCountBirds $naoCountBirds, NAOBirdManager $naoBirdManager)
    {
        $this->naoCountCaptures = $naoCountCaptures;
        $this->naoCountBirds = $naoCountBirds;
        $this->naoBirdManager = $naoBirdManager;
        $this->regions = json_decode(file_get_contents("https://geo.api.gouv.fr/regions"), true);
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

    public function getRegions()
    {
        return $this->regions;
    }

	public function formatBirdsByRegions($year)
	{
        $numberOfPublishedCaptures = $this->naoCountCaptures->countPublishedCapturesByYear($year);
        $regions = $this->regions;

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
        
            
            $regionsData[] = [
                'region' => $regionName,
                'numberOfBirds' => $numberOfBirds,
                'birds' => $birdsName
            ];
        }
        
        $formatted = [];
        $formatted[] = [
            'year' => $year,
            'numberOfCaptures' => $numberOfPublishedCaptures,
            'regions' => $regionsData,
        ];

        return new JsonResponse($formatted);
	}
}