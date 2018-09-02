<?php

namespace App\Controller\Frontend;

use App\Entity\Bird;
use App\Services\Bird\NAOBirdManager;
use App\Services\Bird\NAOCountBirds;
use App\Services\Pagination\NAOPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BirdController extends Controller
{
    /**
     * @Route("repertoire/{page}", defaults={"page"=1}, requirements={"page" = "\d+"}, name="repertory")
     * @param Request $request
     * @param NAOBirdManager $naoBirdManager
     * @param NAOPagination $naoPagination
     * @param NAOCountBirds $naoCountBirds
     * @param $page
     * @return Response
     */
    public function showRepertory(Request $request, NAOBirdManager $naoBirdManager, NAOPagination $naoPagination, NAOCountBirds $naoCountBirds, $page)
    {
        $regions = json_decode(file_get_contents("https://geo.api.gouv.fr/regions"), true);

        $numberOfBirds = $naoCountBirds->countBirds();
        $numberOfBirdsPerPage = $naoPagination->getNbBirdsPerPage();
        $birds = $naoBirdManager->getBirdsPerPage($page, $numberOfBirds, $numberOfBirdsPerPage);
        $nbRepertoryPages = $naoPagination->CountNbPages($numberOfBirds, $numberOfBirdsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);
        if ($request->isMethod('POST')) {
            $region = $request->get('region');
            $letter = $request->get('letter');
            $session = $request->getSession();
            $session->set('region', $region);
            $session->set('letter', $letter);
            return $this->redirectToRoute('result_search_birds');
        }

        return $this->render('bird\repertory.html.twig',
            array(
                'birds' => $birds, 
                'nbRepertoryPages' => $nbRepertoryPages, 
                'nextPage' => $nextPage, 
                'previousPage' => $previousPage, 
                'page' => $page, 
                'regions' => $regions,
            )); 
    }
    
    /**
     * @Route("repertoire/{letter}/{page}", defaults={"page"=1}, requirements={"page" = "\d+"}, name="repertory_by_letter")
     * @param $letter
     * @param $page
     * @param NAOBirdManager $naoBirdManager
     * @param NAOPagination $naoPagination
     * @param NAOCountBirds $naoCountBirds
     * @return Response
     */
    public function showRepertoryByLetter($letter, $page, NAOBirdManager $naoBirdManager, NAOPagination $naoPagination, NAOCountBirds $naoCountBirds)
    {
        $numberOfBirds = $naoCountBirds->countBirdsByLetter($letter);
        $numberOfBirdsPerPage = $naoPagination->getNbBirdsPerPage();
        $birds = $naoBirdManager->getBirdsByLetter($letter, $page, $numberOfBirds, $numberOfBirdsPerPage);
        $nbRepertoryPages = $naoPagination->CountNbPages($numberOfBirds, $numberOfBirdsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);
        $regions = json_decode(file_get_contents("https://geo.api.gouv.fr/regions"), true);

        return $this->render('bird\repertory.html.twig',
            array(
                'birds' => $birds, 
                'nbRepertoryPages' => $nbRepertoryPages, 
                'nextPage' => $nextPage, 
                'previousPage' => $previousPage,
                'page' => $page,
                'letter' => $letter, 
                'regions' => $regions,
            )); 
    }

    /**
     * @Route("resultat-recherche-oiseaux/{page}", defaults={"page"=1}, requirements={"page" = "\d+"}, name="result_search_birds")
     * @param NAOBirdManager $naoBirdManager
     * @param NAOPagination $naoPagination
     * @param NAOCountBirds $naoCountBirds
     * @param $page
     * @return Response
     */
    public function showBirdsByRegion(NAOBirdManager $naoBirdManager, NAOPagination $naoPagination, NAOCountBirds $naoCountBirds, $page)
    {
        $regions = json_decode(file_get_contents("https://geo.api.gouv.fr/regions"), true);
        $session = $request->getSession();
        $letter = $session->get('letter');
        $region = $session->get('region');
        $numberOfSearchBirds = $naoCountBirds->countSearchBirdsByRegionAndLetter($region, $letter);
        $numberOfBirdsPerPage = $naoPagination->getNbBirdsPerPage();
        $birds = $naoBirdManager->searchBirdsByRegionAndLetterPerPage($region, $letter, $page, $numberOfSearchBirds, $numberOfBirdsPerPage);
        $nbRepertoryPages = $naoPagination->CountNbPages($numberOfSearchBirds, $numberOfBirdsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('bird\repertory.html.twig',
            array
            (
                'birds' => $birds, 
                'nbRepertoryPages' => $nbRepertoryPages, 
                'nextPage' => $nextPage, 
                'previousPage' => $previousPage, 
                'page' => $page, 
                'regions' => $regions, 
                'region' => $region
            )); 
    }

    /**
     * @Route("oiseau/{id}", requirements={"id" = "\d+"}, name="bird")
     * @ParamConverter("bird", class="App\Entity\Bird")
     * @param $bird
     * @return Response
     */
    public function showBird(Bird $bird)
    {
        if (!$bird) {
            throw $this->createNotFoundException("Oiseau non trouvé...");
        }

        return $this->render('bird\bird.html.twig', array('bird' => $bird));
    }
}
