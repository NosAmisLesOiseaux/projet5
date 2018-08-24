<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\Security\RegisterType;
use App\Services\Mail\Mailer;
use App\Services\NAOManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $last_username = $authenticationUtils->getLastUsername();
        return $this->render(
            'security/login.html.twig',
            array(
                'error' => $error,
                'last_username' => $last_username
            )
        );
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {}

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param NAOManager $manager
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, NAOManager $manager, Mailer $mailer)
    {
        $user = new User();
        $register_form = $this->createForm(RegisterType::class, $user);
        $register_form->handleRequest($request);
        if ($register_form->isSubmitted() && $register_form->isValid()) {
            $user->setUsername($register_form->getData()->getEmail());
            if ($register_form->getData()->getAccountType() === "naturalist") {
                $user->addRole("ROLE_NATURALIST");
            }
            $encoded = $encoder->encodePassword($user, $register_form->getData()->getPassword());
            $user->setPassword($encoded);
            $manager->addOrModifyEntity($user);
            $mailer->sendConfirmationEmail($user, $user->getActivationCode());
            $this->addFlash('success', "Votre compte a été créé, veuillez confirmer votre adresse e-mail !");
            return $this->redirectToRoute('activation_code');
        }
        return $this->render(
            'security/register.html.twig',
            array(
                'form' => $register_form->createView()
            )
        );
    }

    /**
     * @Route("/activation-code", name="activation_code")
     * @param Request $request
     * @param NAOManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function activationCode(Request $request, NAOManager $manager)
    {
        if ($request->isMethod("POST")) {
            $user = $this->getDoctrine()->getRepository(User::class)->findByActivationCode($request->get('activation_code'));
            if (!$user) {
                $this->addFlash('error', "Ce code n'est pas valide.");
                return $this->redirectToRoute('login');
            }
            $user->setActivationCode(null);
            $user->setActive(true);
            $manager->addOrModifyEntity($user);
            $this->addFlash('success', "Votre compte a bien été activé !");
            return $this->redirectToRoute('login');
        }
        return $this->render(
            'security/activation_code.html.twig'
        );
    }
}
