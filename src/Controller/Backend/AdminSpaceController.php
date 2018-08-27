<?php

namespace App\Controller\Backend;

use App\Entity\Comment;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Comment\NAOCommentManager;
use App\Services\Capture\NAOCountCaptures;
use App\Services\Comment\NAOCountComments;
use App\Services\Pagination\NAOPagination;
use App\Services\User\NAOUserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminSpaceController
 * @package App\Controller\Backend
 * @Route("/espace-administration")
 * @Security("has_role('ROLE_NATURALIST')")
 */
class AdminSpaceController extends Controller
{
    /**
     * @Route("/", name="admin_space")
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @param NAOUserManager $naoUserManager
     * @return Response
     */
    public function showAdminSpaceAction(NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination, NAOUserManager $naoUserManager)
    {
        $user = $this->getUser();
    
        $userRole = $naoUserManager->getRoleFR($user);

        $page = '1';
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();

        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $publishedCaptures = $naoCaptureManager->getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);

        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();
        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);

        $numberOfPublishedComments = $naoCountComments->countPublishedComments();
        $publishedComments = $naoCommentManager->getPublishedCommentsPerPage($page, $numberOfPublishedComments, $numberOfElementsPerPage);

        $numberOfReportedComments  = $naoCountComments->countReportedComments();
        $reportedComments = $naoCommentManager->getReportedCommentsPerPage($page, $numberOfReportedComments, $numberOfElementsPerPage);

        return $this->render('AdminSpace\adminspace.html.twig', array('userRole' => $userRole, 'user' => $user, 'publishedcaptures' => $publishedCaptures, 'waitingforvalidationcaptures' => $waitingForValidationCaptures, 'publishedcomments' => $publishedComments, 'reportedcomments' => $reportedComments, 'numberOfPublishedCaptures' => $numberOfPublishedCaptures, 'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures, 'numberOfPublishedComments' => $numberOfPublishedComments, 'numberOfReportedComments' => $numberOfReportedComments, 'page' => $page, 'numberOfElementsPerPage' => $numberOfElementsPerPage)); 
    }

    /**
     * @Route("/espace-administration/observations-publiees/{page}", defaults={"page"=1}, name="espaceAdminObservationsPubliees", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextPublishedCapturesAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        /*$user = $this->getUser();*/
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneById('4');

        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();

        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $nbPublishedCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures, $numberOfElementsPerPage);
        $publishedCaptures = $naoCaptureManager->getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\publishedCaptures.html.twig', array('publishedcaptures' => $publishedCaptures, 'numberOfPublishedCaptures' => $numberOfPublishedCaptures, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'nbPublishedCapturesPages' => $nbPublishedCapturesPages,)); 
    }

    /**
     * @Route("/espace-administration/observations-en-attente/{page}", defaults={"page"=1}, name="espaceAdminObservationsEnAttente", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextWaitingCapturesAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        /*$user = $this->getUser();*/
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneById('4');

        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();

        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();
        $nbWaitingForValidationCapturesPages = $naoPagination->CountNbPages($numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);
        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\waitingCaptures.html.twig', array('waitingforvalidationcaptures' => $waitingForValidationCaptures, 'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'nbWaitingForValidationCapturesPages' => $nbWaitingForValidationCapturesPages,)); 
    }

    /**
     * @Route("/espace-administration/commentaires-publies/{page}", defaults={"page"=1}, name="espaceAdminCommentairesPublies", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextPublishedCommentsAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        /*$user = $this->getUser();*/
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneById('4');

        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();

        $numberOfPublishedComments = $naoCountComments->countPublishedComments();
        $nbPublishedCommentsPages = $naoPagination->CountNbPages($numberOfPublishedComments, $numberOfElementsPerPage);
        $publishedComments = $naoCommentManager->getPublishedCommentsPerPage($page, $numberOfPublishedComments, $numberOfElementsPerPage);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\publishedComments.html.twig', array('publishedcomments' => $publishedComments, 'numberOfPublishedComments' => $numberOfPublishedComments, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage, 'nbPublishedCommentsPages' => $nbPublishedCommentsPages,)); 
    }

    /**
     * @Route("/espace-administration/commentaires-signales/{page}", defaults={"page"=1},  name="espaceAdminCommentairesSignales", requirements={"page" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function showNextReportedCommentsAction($page, NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        /*$user = $this->getUser();*/
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneById('4');


        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();

        $numberOfReportedComments  = $naoCountComments->countReportedComments();
        $nbReportedCommentsPages = $naoPagination->CountNbPages($numberOfReportedComments, $numberOfElementsPerPage);
        $reportedComments = $naoCommentManager->getReportedCommentsPerPage($page, $numberOfReportedComments, $numberOfElementsPerPage);

        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render('AdminSpace\reportedComments.html.twig', array('reportedcomments' => $reportedComments, 'numberOfReportedComments' => $numberOfReportedComments, 'page' => $page, 'nextPage' => $nextPage, 'previousPage' => $previousPage,'nbReportedCommentsPages' => $nbReportedCommentsPages)); 
    }

    /**
     * @Route("/ignorer-commentaire-signale/{id}", name="ignorerCommentaireSignale", requirements={"id" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function ignoreReportedCommentAction($id, NAOCommentManager $naoCommentManager, NAOManager $naoManager)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->findOneById($id);

        $naoCommentManager->ignoreReportedComment($comment);
        $naoManager->addOrModifyEntity($comment);

        return $this->redirectToRoute('espaceAdministration');
    }

    /**
     * @Route("/supprimer-commentaire/{id}", name="supprimerCommentaire", requirements={"id" = "\d+"})
     * @param Request $request
     * @return Response
     */
    public function removeCommentAction($id, NAOManager $naoManager)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comment::class)->findOneById($id);

        $naoManager->removeEntity($comment);

        return $this->redirectToRoute('espaceAdministration');
    }

    /**
     * @Route(path="add-csv-file", name="add_csv_file")
     * @param Request $request
     * @return Response
     */
    public function addCsvFile(Request $request)
    {
        $form = $this->createForm(Image::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData());
            die;
        }
        return $this->render(
            'admin/add_csv_file.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
