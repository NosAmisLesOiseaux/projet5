<?php

namespace App\Controller\Backend;

use App\Entity\Capture;
use App\Entity\Comment;
use App\Entity\User;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Comment\NAOCommentManager;
use App\Services\Capture\NAOCountCaptures;
use App\Services\Comment\NAOCountComments;
use App\Services\Pagination\NAOPagination;
use App\Services\User\NAOUserManager;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminSpaceController
 * @package App\Controller\Backend
 * @Route("/espace-administration")
 */
class AdminSpaceController extends Controller
{
    /**
     * @Route("/", name="admin_space")
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @param NAOUserManager $naoUserManager
     * @param Request $request
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
        return $this->render(
            'AdminSpace\adminspace.html.twig',
            array(
                'userRole' => $userRole,
                'user' => $user,
                'publishedcaptures' => $publishedCaptures,
                'waitingforvalidationcaptures' => $waitingForValidationCaptures,
                'publishedcomments' => $publishedComments,
                'reportedcomments' => $reportedComments,
                'numberOfPublishedCaptures' => $numberOfPublishedCaptures,
                'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures,
                'numberOfPublishedComments' => $numberOfPublishedComments,
                'numberOfReportedComments' => $numberOfReportedComments,
                'page' => $page,
                'numberOfElementsPerPage' => $numberOfElementsPerPage
            )
        );
    }

    /**
     * @Route("/observations-publiees/{page}", defaults={"page"=1}, name="admin_space_published_captures", requirements={"page" = "\d+"})
     * @param $page
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @param Request $request
     * @return Response
     */
    public function showNextPublishedCapturesAction($page, NAOCaptureManager $naoCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $nbPublishedCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures, $numberOfElementsPerPage);
        $publishedCaptures = $naoCaptureManager->getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        return $this->render(
            'AdminSpace\publishedCaptures.html.twig',
            array(
                'publishedcaptures' => $publishedCaptures,
                'numberOfPublishedCaptures' => $numberOfPublishedCaptures,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbPublishedCapturesPages' => $nbPublishedCapturesPages
            )
        );
    }

    /**
     * @Route("/observations-en-attente/{page}", defaults={"page"=1}, name="admin_space_waiting_captures", requirements={"page" = "\d+"})
     * @param $page
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @param Request $request
     * @return Response
     */
    public function showNextWaitingCapturesAction($page, NAOCaptureManager $naoCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();
        $nbWaitingForValidationCapturesPages = $naoPagination->CountNbPages($numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);
        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);
        return $this->render(
            'AdminSpace\waitingCaptures.html.twig',
            array(
                'waitingforvalidationcaptures' => $waitingForValidationCaptures,
                'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbWaitingForValidationCapturesPages' => $nbWaitingForValidationCapturesPages
            )
        );
    }

    /**
     * @Route("/commentaires-publies/{page}", defaults={"page"=1}, name="admin_space_published_comments", requirements={"page" = "\d+"})
     * @param $page
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @param Request $request
     * @return Response
     */
    public function showNextPublishedCommentsAction($page, NAOCommentManager $naoCommentManager, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfPublishedComments = $naoCountComments->countPublishedComments();
        $nbPublishedCommentsPages = $naoPagination->CountNbPages($numberOfPublishedComments, $numberOfElementsPerPage);
        $publishedComments = $naoCommentManager->getPublishedCommentsPerPage($page, $numberOfPublishedComments, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);
        return $this->render(
            'AdminSpace\publishedComments.html.twig',
            array(
                'publishedcomments' => $publishedComments,
                'numberOfPublishedComments' => $numberOfPublishedComments,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbPublishedCommentsPages' => $nbPublishedCommentsPages
            )
        );
    }

    /**
     * @Route("/commentaires-signales/{page}", defaults={"page"=1},  name="admin_space_reported_comments", requirements={"page" = "\d+"})
     * @param $page
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @param Request $request
     * @return Response
     */
    public function showNextReportedCommentsAction($page, NAOCommentManager $naoCommentManager, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfReportedComments  = $naoCountComments->countReportedComments();
        $nbReportedCommentsPages = $naoPagination->CountNbPages($numberOfReportedComments, $numberOfElementsPerPage);
        $reportedComments = $naoCommentManager->getReportedCommentsPerPage($page, $numberOfReportedComments, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);
        return $this->render(
            'AdminSpace\reportedComments.html.twig',
            array(
                'reportedcomments' => $reportedComments,
                'numberOfReportedComments' => $numberOfReportedComments,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbReportedCommentsPages' => $nbReportedCommentsPages
            )
        );
    }

    /**
     * @Route("/ignorer-commentaire-signale/{id}", name="ignore_reported_comment", requirements={"id" = "\d+"})
     * @param $id
     * @param NAOCommentManager $naoCommentManager
     * @param NAOManager $naoManager
     * @return Response
     */
    public function ignoreReportedCommentAction($id, NAOCommentManager $naoCommentManager, NAOManager $naoManager)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->findOneById($id);
        $naoCommentManager->ignoreReportedComment($comment);
        $naoManager->addOrModifyEntity($comment);
        return $this->redirectToRoute('espaceAdministration');
    }

    /**
     * @Route("/supprimer-commentaire/{id}", name="remove_comment", requirements={"id" = "\d+"})
     * @param $id
     * @param NAOManager $naoManager
     * @return Response
     */
    public function removeCommentAction($id, NAOManager $naoManager)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->findOneById($id);
        $naoManager->removeEntity($comment);
        return $this->redirectToRoute('espaceAdministration');
    }
}