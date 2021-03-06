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

    public function getNumberOfBirdsByRegion($year)
    {
        $regions = $this->regions;
        $numberOfBirdsRegions = [];

        foreach ($regions as $region)
        {
            $regionName = $region['nom'];
            $numberOfBirdsRegions[] = $this->naoCountBirds->countSearchBirdsByRegionAndDate($regionName, $year);
        }
        
        return $numberOfBirdsRegions;
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

            $birdsData = [];
            foreach ($birds as $bird) {
                $numberCapturesByBirdAndRegionAndYear = $this->naoCountCaptures->countCapturesByBirdAndRegionAndYear($bird, $regionName, $year);
                if ($bird->getVernacularname() != null)
                {
                    $birdsData[] = [
                            'birdName' => $bird->getVernacularname().' - '.$bird->getValidname(),
                            'observations' => $numberCapturesByBirdAndRegionAndYear,
                        ];
                    }
                    else 
                    {
                        $birdsData[] = [
                            'birdName' => $bird->getValidname(),
                            'observations' => $numberCapturesByBirdAndRegionAndYear,
                        ];
                    }
                }
        
            
            $regionsData[] = [
                'region' => $regionName,
                'numberOfBirds' => $numberOfBirds,
                'birds' => $birdsData
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