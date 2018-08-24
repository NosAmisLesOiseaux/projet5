<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Bird;
use App\Services\Comment\NAOCommentManager;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Comment\NAOCountComments;
use App\Services\Capture\NAOCountCaptures;
use App\Services\Pagination\NAOPagination;
use App\Services\Bird\NAOBirdManager;
use App\Form\Comment\CommentType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CaptureController extends Controller
{
    /**
     * @Route("observation/{id}", requirements={"id" = "\d+"}, name="capture")
     * @param $id
     * @param Request $request
     * @param NAOManager $naoManager
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCountComments $naoCountComments
     * @return Response
     */
    public function showCaptureAction($id, Request $request, NAOManager $naoManager, NAOCaptureManager $naoCaptureManager, NAOCountComments $naoCountComments, ValidatorInterface $validator, NAOCommentManager $commentManager)
    {
        $capture = $naoCaptureManager->getPublishedCapture($id);
        if ($capture === null) {
            return $this->redirectToRoute('observations');
        }
        $numberOfCaptureComments = $naoCountComments->countCapturePublishedComments($capture);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) 
        {
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setCapture($capture);
            $listErrors = $validator->validate($comment);
            if(count($listErrors) > 0) {
                return new Response((string) $listErrors);
            } else {
                $naoManager->addOrModifyEntity($comment);
                $this->addFlash('success',"Commentaire ajouté !");
                return $this->redirectToRoute('capture', ['id' => $capture->getId()]);
            }
        }
        return $this->render('Capture\showCapture.html.twig', 
            array
            (
                'capture' => $capture,
                'numberOfCaptureComments' => $numberOfCaptureComments,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * @Route("/observations/{pageNumber}", requirements={"pageNumber" = "\d+"}, defaults={"pageNumber"=1}, name="captures")
     * @param Request $request
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOPagination $naoPagination
     * @param NAOBirdManager $naoBirdManager
     * @param $pageNumber
     * @return Response
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
        if ($request->isMethod('POST')) {
            $birdName = $request->get('bird');
            $bird = $naoBirdManager->getBirdByVernacularOrValidName($birdName);
            $region = $request->get('region');
            $session = $request->getSession();
            $session->set('bird', $bird);
            $session->set('region', $region);

            return $this->redirectToRoute('result_search_captures');
        }

        return $this->render('Capture\showCaptures.html.twig', 
            array
            (
                'captures' => $captures, 
                'pageNumber' => $pageNumber, 
                'nbCapturesPages' => $nbCapturesPages, 
                'nextPage' => $nextPage, 
                'previousPage' => $previousPage, 
                'birds' => $birds, 
                'regions' => $regions
            ));
    }

    /**
     * @Route("/resultat-recherche-observations/{pageNumber}", requirements={"pageNumber" = "\d+"}, defaults={"pageNumber"=1}, name="result_search_captures")
     * @param Request $request
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCountCaptures $naoCountCaptures
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

        return $this->render('Capture\showCaptures.html.twig', 
            array
            (
                'captures' => $capturesSearch, 
                'pageNumber' => $pageNumber, 
                'nbCapturesPages' => $nbCapturesPages, 
                'nextPage' => $nextPage, 
                'previousPage' => $previousPage, 
                'birds' => $birds, 
                'regions' => $regions, 
                'resultats' => $resultats
            ));
    }

     /**
     * @Route("/signaler-commentaire/{comment}", requirements={"id" = "\d+"}, name="reported_comment")
     * @ParamConverter("comment", class="App\Entity\Comment")
     * @param NAOManager $naoManager
     * @param NAOCommentManager $naoCommentManager
     * @return Response
     */
    public function reportCommentAction(Comment $comment, NAOManager $naoManager, NAOCommentManager $naoCommentManager, Request $request)
    {
        $referer = $request->headers->get('referer');
        $naoCommentManager->reportComment($comment);
        $naoManager->addOrModifyEntity($comment);
        return $this->redirect($referer);
    }
}
