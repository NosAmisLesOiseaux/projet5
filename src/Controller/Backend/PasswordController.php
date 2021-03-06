<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\Password\LostPasswordType;
use App\Form\Password\ReinitialisationPasswordType;
use App\Services\Mail\Mailer;
use App\Services\NAOManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PasswordController
 * @package App\Controller\Backend
 * @Route("/mot-de-passe")
 */
class PasswordController extends Controller
{
    /**
     * @Route("/oublie", name="lost_password")
     * @param Request $request
     * @param Mailer $mailer
     * @param NAOManager $NAOManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function lostPassword(Request $request, Mailer $mailer, NAOManager $NAOManager)
    {
        $lost_password_form = $this->createForm(LostPasswordType::class);
        $lost_password_form->handleRequest($request);
        if ($lost_password_form->isSubmitted() && $lost_password_form->isValid()) {
            $em = $this->getDoctrine();
            $user = $em->getRepository(User::class)->findUserByEmail($lost_password_form->getData()['email']);
            if (!$user) {
                $this->addFlash('error', "Vous n'êtes pas enregistré sur le site");
                return $this->redirectToRoute('register');
            }
            $user->setToken(md5(uniqid("token_", true)));
            $mailer->sendLostPasswordEmail($user);
            $NAOManager->addOrModifyEntity($user);
            return $this->redirectToRoute('password_reinitialisation_pending');
        }
        return $this->render(
            'password/lost_password.html.twig',
            array(
                'form' => $lost_password_form->createView()
            )
        );
    }

    /**
     * @Route("/reinitialisation-en-attente", name="password_reinitialisation_pending")
     * @return Response
     */
    public function passwordReinitialisationPending()
    {
        return $this->render(
            'password/pending.html.twig'
        );
    }

    /**
     * @Route("/reinitialisation/{token}", name="password_reinitialisation")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param NAOManager $NAOManager
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function reinitialisationPassword(Request $request, UserPasswordEncoderInterface $encoder, NAOManager $NAOManager, Mailer $mailer)
    {
        $em = $this->getDoctrine();
        $token = $request->get('token');
        $user = $em->getRepository(User::class)->findUserByToken($token);
        if (!$user) {
            $this->addFlash('error', "Vous n'êtes pas autorisé à accéder à cette page !");
            return $this->redirectToRoute('index');
        }
        $form = $this->createForm(ReinitialisationPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** Optimiser cette partie */
            if ($user instanceof UserInterface) {
                $encoded = $encoder->encodePassword($user, $form->getData()->getPassword());
                $user = $this->getDoctrine()->getRepository(User::class)->findUserByToken($token);
                $user->setPassword($encoded);
                $user->setToken(null);
                $NAOManager->addOrModifyEntity($user);
                $mailer->sendPasswordReinitialisationSuccessEmail($user);
                $this->addFlash('success', "Votre mot de passe a bien été mis à jour !");
                return $this->redirectToRoute('login');
            } else {
                $this->addFlash('error', "Un problème est survenu durant la réinitialisation du mot de passe.");
                return $this->redirectToRoute('lost_password');
            }
        }
        return $this->render(
            'password/reinitialisation_password.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
