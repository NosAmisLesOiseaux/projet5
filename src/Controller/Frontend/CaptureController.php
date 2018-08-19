<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Bird;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Comment\NAOCountComments;
use App\Services\Capture\NAOCountCaptures;
use App\Services\NAOPagination;
use App\Services\Bird\NAOBirdManager;
use App\Services\Capture\NAOShowMap;
use App\Form\CommentType;
use App\Form\Capture\SearchCaptureType;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CaptureController extends Controller
{
    /**
     * @Route("observation/{id}", requirements={"id" = "\d+"}, name="observation")
     * @param $id
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCountComments $naoCountComments
     * @return Response
     */
    public function showCaptureAction($id, Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOCountComments $naoCountComments)
    {
        $capture = $naoCaptureManager->getPublishedCapture($id);
        
        $numberOfCaptureComments = $naoCountComments->countCapturePublishedComments($capture);

        $comment = new Comment();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setCapture($capture);

            $validator = $this->get('validator');
            $listErrors = $validator->validate($comment);
            if(count($listErrors) > 0) 
            {
                return new Response((string) $listErrors);
            } 
            else 
            {
                $naoManager->addOrModifyEntity($comment);
                $this->get('session')->getFlashBag()->add('success',"Commentaire ajouté !");
                return $this->redirectToRoute('observation', ['id' => $capture->getId()]);
            }
        }

        return $this->render('Capture\showCapture.html.twig', array('capture' => $capture, 'id' => $id, 'numberOfCaptureComments' => $numberOfCaptureComments, 'form' => $form->createView(),)); 
    }

    /**
     * @Route("/observations/{pageNumber}", requirements={"pageNumber" = "\d+"}, defaults={"pageNumber"=1}, name="observations")
     * @param Request $request
     * @param NAOCaptureManager $nAOCaptureManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOPagination $naoPagination
     * @param NAOBirdManager $naoBirdManager
     * @param $pageNumber
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function showCapturesAction(Request $request, NAOCaptureManager $nAOCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination, NAOBirdManager $naoBirdManager, $pageNumber)
    {
        $regions = json_decode(file_get_contents("https://geo.api.gouv.fr/regions"), true);
        $birds = $this->getDoctrine()->getRepository(Bird::class)->getBirdsByOrderAsc();

        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $numberOfPublishedCapturesPerPage = $naoPagination->getNbElementsPerPage();

        $captures = $nAOCaptureManager->getPublishedCapturesPerPage($pageNumber, $numberOfPublishedCaptures, $numberOfPublishedCapturesPerPage);

        $nbCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures, $numberOfPublishedCapturesPerPage);

        $nextPage = $naoPagination->getNextPage($pageNumber);
        $previousPage = $naoPagination->getPreviousPage($pageNumber);

        if ($request->isMethod('POST'))
        {
            $birdName = $request->get('bird');
            $bird = $naoBirdManager->getBirdByVernacularOrValidName($birdName);

            $region = $request->get('region');
            $session = $request->getSession();
            $session->set('bird', $bird);
            $session->set('region', $region);

            return $this->redirectToRoute('resultatRechercheObservations');
        }

        return $this->render('Capture\showCaptures.html.twig', array('captures' => $captures, 'pageNumber' => $pageNumber, 'nbCapturesPages' => $nbCapturesPages, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'birds' => $birds, 'regions' => $regions));
    }

    /**
     * @Route("/resultat-recherche-observations/{pageNumber}", requirements={"pageNumber" = "\d+"}, defaults={"pageNumber"=1}, name="resultatRechercheObservations")
     * @param Request $request
     * @param NAOCaptureManager $nAOCaptureManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOPagination $naoPagination
     * @param $pageNumber
     * @return Response
     */
    public function showCapturesSearchAction(Request $request, NAOCaptureManager $nAOCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination, $pageNumber)
    {
        $regions = json_decode(file_get_contents("https://geo.api.gouv.fr/regions"), true);
        $birds = $this->getDoctrine()->getRepository(Bird::class)->getBirdsByOrderAsc();
        $resultats = 'résultats';

        $session = $request->getSession();
        $bird = $session->get('bird');
        $region = $session->get('region');

        $numberOfPublishedCapturesPerPage = $naoPagination->getNbElementsPerPage();

        $numberOfSearchCaptures = $naoCountCaptures->countSearchCapturesByBirdAndRegion($bird, $region);

        $capturesSearch =  $nAOCaptureManager->searchCapturesByBirdAndRegionPerPage($bird, $region, $pageNumber, $numberOfSearchCaptures, $numberOfPublishedCapturesPerPage);

        $nbCapturesPages = $naoPagination->CountNbPages($numberOfSearchCaptures, $numberOfPublishedCapturesPerPage);

        $nextPage = $naoPagination->getNextPage($pageNumber);
        $previousPage = $naoPagination->getPreviousPage($pageNumber);

        return $this->render('Capture\showCaptures.html.twig', array('captures' => $capturesSearch, 'pageNumber' => $pageNumber, 'nbCapturesPages' => $nbCapturesPages, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'birds' => $birds, 'regions' => $regions, 'resultats' => $resultats));
    }
}
