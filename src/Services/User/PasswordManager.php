<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 17/08/18
 * Time: 23:39
 */

namespace App\Services\User;


use App\Entity\User;
use App\Services\Mail\Mailer;
use App\Services\NAOManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordManager
{
    private $encoder;

    private $mailer;

    private $session;

    private $naoManager;

    public function __construct(UserPasswordEncoderInterface $encoder, Mailer $mailer, SessionInterface $session, NAOManager $naoManager)
    {
        $this->encoder = $encoder;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->naoManager = $naoManager;
    }

    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function changePassword(User $user, string $password)
    {
        /** @var User $user */
        $user = $this->naoManager->getEm()->getRepository(User::class)->findUserByEmail($user->getEmail());
        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        $this->naoManager->addOrModifyEntity($user);
        $this->mailer->sendConfirmationPasswordChanged($user);
        $this->session->getFlashBag()->add('success', "Votre mot de passe a été changé avec succès !");
        return true;
    }
}
