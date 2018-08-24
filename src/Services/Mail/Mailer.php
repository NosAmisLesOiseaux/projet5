<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 03/08/18
 * Time: 21:25
 */

namespace App\Services\Mail;


use App\Entity\User;
use Psr\Container\ContainerInterface;

class Mailer
{
    private $mailer;

    private $container;

    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    /**
     * @param User $user
     * @param string $code
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendConfirmationEmail(User $user, string $code)
    {
        $message = (new \Swift_Message("Activation de votre compte NAO"))
            ->setFrom("sent@nao.fr")
            ->setTo($user->getEmail())
            ->setBody(
                $this->container->get('twig')->render('emails/account_activation.html.twig', ['user' => $user, 'code' => $code])
            );
        $this->mailer->send($message);
        return true;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendConfirmationPasswordChanged(User $user)
    {
        $message = (new \Swift_Message("Changement de votre mot de passe"))
            ->setFrom("sent@nao.fr")
            ->setTo($user->getEmail())
            ->setBody(
                $this->container->get('twig')->render('emails/password_changed.html.twig', ['user' => $user])
            );
        $this->mailer->send($message);
        return true;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendLostPasswordEmail(User $user)
    {
        $message = (new \Swift_Message("Réinitialisation de votre mot de passe"))
            ->setFrom("sent@nao.fr")
            ->setTo($user->getEmail())
            ->setBody(
                $this->container->get('twig')->render('emails/lost_password.html.twig', ['user' => $user])
            );
        $this->mailer->send($message);
        return true;
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendPasswordReinitialisationSuccessEmail(User $user)
    {
        $message = (new \Swift_Message("Mot de passe réinitialisé avec succès !"))
            ->setFrom("sent@nao.fr")
            ->setTo($user->getEmail())
            ->setBody(
                $this->container->get('twig')->render('emails/password_reinitialisation_success.html.twig')
            );
        $this->mailer->send($message);
        return true;
    }
}
