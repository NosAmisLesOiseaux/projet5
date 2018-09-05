<?php

namespace App\Controller\Frontend;

use App\Entity\Capture;
use App\Entity\Message;
use App\Form\Contact\MessageType;
use App\Services\Bird\NAOCountBirds;
use App\Services\Capture\NAOCountCaptures;
use App\Services\NAOManager;
use App\Services\Capture\NAOCaptureManager;
use App\Services\Statistics\NAODataStatistics;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
	/**
     * @Route("/", name="home")
     * @param NAOCaptureManager $naoCaptureManager
     * @return Response
     */
	public function showHome(NAOCaptureManager $naoCaptureManager)
	{
		$captures = $naoCaptureManager->getLastPublishedCaptures();

        return $this->render('home/index.html.twig', array('captures' => $captures));
	}

    /**
     * @Route("/statistiques/{year}", name="statistics", defaults={"year"=2018})
     * @param NAODataStatistics $naoDataStatistics
     * @param NAOCountCaptures $naoCountCaptures
     * @param NAOCountBirds $naoCountBirds
     * @param $year
     * @return Response
     */
    public function statistics(NAODataStatistics $naoDataStatistics, NAOCountCaptures $naoCountCaptures, NAOCountBirds $naoCountBirds, $year)
    {
        $years = $naoDataStatistics->getYears();
        $numberOfPublishedCaptures = $naoCountCaptures->countPublishedCaptures();
        $numberOfBirds = $naoCountBirds->countBirds();
        $nbOfYearPublishedCaptures = $naoCountCaptures->countPublishedCapturesByYear($year);
        $regions=$naoDataStatistics->getRegions();
        return $this->render(
            'statistics/statistics.html.twig',
            array(
                    'numberOfBirds' => $numberOfBirds,
                    'numberOfPublishedCaptures' => $numberOfPublishedCaptures,
                    'years' => $years,
                    'year' => $year,
                    'nbOfYearPublishedCaptures' => $nbOfYearPublishedCaptures,
                    'regions' => $regions,
                )
        );
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request)
    {
        $message = new Message();
        $message_form = $this->createForm(MessageType::class, $message);
        $message_form->handleRequest($request);
        if ($message_form->isSubmitted() && $message_form->isValid()) {
            $message->setSentAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', "Message envoyé");
            return $this->redirectToRoute('contact');
        }
        return $this->render(
            'contact/contact.html.twig',
            array(
                'form' => $message_form->createView()
            )
        );
    }
}
