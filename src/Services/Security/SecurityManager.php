<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 23/08/18
 * Time: 23:40
 */

namespace App\Services\Security;


use App\Entity\User;
use App\Services\Mail\Mailer;
use App\Services\NAOManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityManager
{
    private $encoder;

    private $mailer;

    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder, Mailer $mailer, NAOManager $manager)
    {
        $this->encoder = $encoder;
        $this->mailer = $mailer;
        $this->manager = $manager;
    }

    public function register(User $user)
    {
        $user->setUsername($user->getEmail());
        if ($user->getAccountType() === "naturalist") {
            $user->addRole("ROLE_NATURALIST");
        }
        $encoded = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);
        $this->manager->addOrModifyEntity($user);
        $this->mailer->sendConfirmationEmail($user, $user->getActivationCode());
    }
}
