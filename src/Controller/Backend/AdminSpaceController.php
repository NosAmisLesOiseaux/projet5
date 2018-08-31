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
    public function showAdminSpace(NAOCaptureManager $naoCaptureManager, NAOCommentManager $naoCommentManager, NAOCountCaptures $naoCountCaptures, NAOCountComments $naoCountComments, NAOPagination $naoPagination, NAOUserManager $naoUserManager)
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
            'admin/admin_space.html.twig',
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
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOPagination $naoPagination
     * @return Response
     */
    public function showNextPublishedCaptures($page, NAOCaptureManager $naoCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $nbPublishedCapturesPages = $naoPagination->CountNbPages($numberOfPublishedCaptures, $numberOfElementsPerPage);
        $publishedCaptures = $naoCaptureManager->getPublishedCapturesPerPage($page, $numberOfPublishedCaptures, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        $subtitle = 'Observations publiées';
        $template = 'admin/_published_captures_model.html.twig';
        $url = 'admin_space_published_captures';

        return $this->render(
            'admin/next_elements.html.twig',
            array(
                'publishedcaptures' => $publishedCaptures,
                'numberOfPublishedCaptures' => $numberOfPublishedCaptures,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbElementsPages' => $nbPublishedCapturesPages,
                'subtitle' => $subtitle, 
                'template' => $template, 
                'url' => $url
            )
        );
    }

    /**
     * @Route("/observations-en-attente/{page}", defaults={"page"=1}, name="admin_space_waiting_captures", requirements={"page" = "\d+"})
     * @param $page
     * @param NAOCaptureManager $naoCaptureManager
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOPagination $naoPagination
     * @return Response
     */
    public function showNextWaitingCaptures($page, NAOCaptureManager $naoCaptureManager, NAOCountCaptures $naoCountCaptures, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfWaitingForValidationCaptures = $naoCountCaptures->countWaitingForValidationCaptures();
        $nbWaitingForValidationCapturesPages = $naoPagination->CountNbPages($numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);
        $waitingForValidationCaptures = $naoCaptureManager->getWaintingForValidationCapturesPerPage($page, $numberOfWaitingForValidationCaptures, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        $subtitle = 'Observations en attente de validation';
        $template = 'admin/_waiting_captures_model.html.twig';
        $url = 'admin_space_waiting_captures';

        return $this->render(
            'admin/next_elements.html.twig',
            array(
                'waitingforvalidationcaptures' => $waitingForValidationCaptures,
                'numberOfWaitingforvalidationCaptures' => $numberOfWaitingForValidationCaptures,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbElementsPages' => $nbWaitingForValidationCapturesPages,
                'subtitle' => $subtitle, 
                'template' => $template, 
                'url' => $url
            )
        );
    }

    /**
     * @Route("/commentaires-publies/{page}", defaults={"page"=1}, name="admin_space_published_comments", requirements={"page" = "\d+"})
     * @param $page
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @return Response
     */
    public function showNextPublishedComments($page, NAOCommentManager $naoCommentManager, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfPublishedComments = $naoCountComments->countPublishedComments();
        $nbPublishedCommentsPages = $naoPagination->CountNbPages($numberOfPublishedComments, $numberOfElementsPerPage);
        $publishedComments = $naoCommentManager->getPublishedCommentsPerPage($page, $numberOfPublishedComments, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        $subtitle = 'Commentaires publiés';
        $template = 'admin/_published_comments_model.html.twig';
        $url = 'admin_space_published_comments';

        return $this->render(
            'admin/next_elemnts.html.twig',
            array(
                'publishedcomments' => $publishedComments,
                'numberOfPublishedComments' => $numberOfPublishedComments,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbElementsPages' => $nbPublishedCommentsPages,
                'subtitle' => $subtitle, 
                'template' => $template, 
                'url' => $url
            )
        );
    }

    /**
     * @Route("/commentaires-signales/{page}", defaults={"page"=1},  name="admin_space_reported_comments", requirements={"page" = "\d+"})
     * @param $page
     * @param NAOCommentManager $naoCommentManager
     * @param NAOCountComments $naoCountComments
     * @param NAOPagination $naoPagination
     * @return Response
     */
    public function showNextReportedComments($page, NAOCommentManager $naoCommentManager, NAOCountComments $naoCountComments, NAOPagination $naoPagination)
    {
        $numberOfElementsPerPage = $naoPagination->getNbElementsPerPage();
        $numberOfReportedComments  = $naoCountComments->countReportedComments();
        $nbReportedCommentsPages = $naoPagination->CountNbPages($numberOfReportedComments, $numberOfElementsPerPage);
        $reportedComments = $naoCommentManager->getReportedCommentsPerPage($page, $numberOfReportedComments, $numberOfElementsPerPage);
        $nextPage = $naoPagination->getNextPage($page);
        $previousPage = $naoPagination->getPreviousPage($page);

        $subtitle = 'Commentaires signalés';
        $template = 'admin/_reported_comments_model.html.twig';
        $url = 'admin_space_reported_comments'

        return $this->render(
            'admin/next_elements.html.twig',
            array(
                'reportedcomments' => $reportedComments,
                'numberOfReportedComments' => $numberOfReportedComments,
                'page' => $page,
                'nextPage' => $nextPage,
                'previousPage' => $previousPage,
                'nbElementsPages' => $nbReportedCommentsPages,
                'subtitle' => $subtitle, 
                'template' => $template, 
                'url' => $url
            )
        );
    }

    /**
     * @Route("/ignorer-commentaire-signale/{id}", name="ignore_reported_comment", requirements={"id" = "\d+"})
     * @param $id
     * @param NAOCommentManager $naoCommentManager
     * @param NAOManager $naoManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ignoreReportedComment($id, NAOCommentManager $naoCommentManager, NAOManager $naoManager)
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->findOneById($id);
        $naoCommentManager->ignoreReportedComment($comment);
        $naoManager->addOrModifyEntity($comment);
        return $this->redirectToRoute('admin_space');
    }

    /**
     * @Route("/supprimer-commentaire/{id}", name="remove_comment", requirements={"id" = "\d+"})
     * @ParamConverter("comment", class="App\Entity\Comment")
     * @param Comment $comment
     * @param NAOManager $naoManager
     * @return Response
     */
    public function removeComment(Comment $comment, NAOManager $naoManager)
    {
        $naoManager->removeEntity($comment);
        return $this->redirectToRoute('admin_space');
    }
}
