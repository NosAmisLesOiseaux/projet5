<?php

// src/Services/User/NAOUserManager.php

namespace App\Services\User;

use App\Repository\UserRepository;
use App\Services\NAOManager;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NAOUserManager extends NAOManager
{
    private $container;

    private $userRepository;

    private $manager;

    private $session;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container, UserRepository $userRepository, NAOManager $manager, SessionInterface $session)
    {
        parent::__construct($em);
        $this->container = $container;
        $this->userRepository = $userRepository;
        $this->manager = $manager;
        $this->session = $session;
    }

    public function getCurrentUser(string $username)
    {
        $current_user = $this->userRepository->loadUserByUsername($username);
        return $current_user;
    }

    /**
     * @param User $user
     * @return null|string
     */
	public function getRoleFR(User $user): ?string
	{
		$roles = $user->getRoles();
		if (in_array('ROLE_ADMIN', $roles)) {
		    return "administrateur";
        } elseif (in_array('ROLE_NATURALIST', $roles)) {
		    return "naturaliste";
        } else {
		    return "particulier";
        }
	}

    /**
     * @param User $user
     * @param $biography
     * @return bool
     */
	public function changeBiography(User $user, $biography)
    {
        $user->setBiography($biography);
        $this->manager->addOrModifyEntity($user);
        return true;
    }

    /**
     * @param User $user
     * @return null|string
     */
	public function getNaturalistOrParticularRole(User $user): ?string
	{
		$roles = $user->getRoles();

        if ((in_array('ROLE_ADMIN', $roles)) or (in_array('ROLE_NATURALIST', $roles)))
        {
            return 'naturalist';
        }
        elseif (in_array('ROLE_USER', $roles))
        {
           return 'particular';
        }
	}

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
