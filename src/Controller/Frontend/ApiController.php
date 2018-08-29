<?php

namespace App\Controller\Frontend;

use App\Services\Capture\NAOCaptureManager;
use App\Services\Map\NAOShowMap;
use App\Services\Comment\NAOCommentManager;
use App\Services\Comment\NAOShowComments;
use App\Services\Statistics\NAODataStatistics;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller\Frontend
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/publishedcaptures", name="app_publishedcaptures_list", methods={"GET"})
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOShowMap $naoShowMap
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getPublishedCapturesData(NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getPublishedCaptures();

        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Route(path="/birdpublishedcaptures/{id}", name="app_birdpublishedcaptures_list", requirements={"id" = "\d+"}, methods={"GET"})
     * @param $id
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOShowMap $naoShowMap
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getBirdPublishedCapturesData($id, NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $captures = $naoCaptureManager->getBirdPublishedCaptures($id);

        return $publishedCaptures = $naoShowMap->formatPublishedCaptures($captures);
    }

    /**
     * @Route(path="/latloncapture/{id}", name="app_publishedcapture", requirements={"id" = "\d+"}, methods={"GET"})
     * @param $id
     * @param NAOShowMap $naoShowMap
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getLatitudeLongitudeCapture($id, NAOShowMap $naoShowMap)
    {
        return $capture = $naoShowMap->formatCapture($id);
    }

    /**
     * @Route(path="/lastcaptures", name="app_lastcaptures_list", methods={"GET"})
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOShowMap $naoShowMap
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showLastCapturesAction(NAOCaptureManager $naoCaptureManager, NAOShowMap $naoShowMap)
    {
        $lastCaptures = $naoCaptureManager->getLastPublishedCaptures();
        return $naoShowMap->formatPublishedCaptures($lastCaptures);
    }

    /**
     * @Route(path="/capturepublishedcomments/{id}", name="app_capturepublishedcomments", requirements={"id" = "\d+"}, methods={"GET"})
     * @param $id
     * @param NAOShowComments $naoShowComments
     * @param NAOCommentManager $naoCommentManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getCapturePublishedComments($id, NAOShowComments $naoShowComments, NAOCommentManager $naoCommentManager)
    {
        $comments = $naoCommentManager->getCapturePublishedComments($id);

        return $capturepublishedcomments = $naoShowComments->formatCapturePublishedComments($comments);
    }

    /**
     * @Route(path="/datastatistics/{year}", name="app_data_statistics", methods={"GET"})
     * @param NAODataStatistics $naoDataStatistics
     * @param $year
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showDataStatics(NAODataStatistics $naoDataStatistics, $year)
    {
        return $naoDataStatistics->formatBirdsByRegions($year);
    }
}
