<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @ORM\Column(name="lastname", type="string")
     */
    private $lastname;

    /**
     * @ORM\Column(name="firstname", type="string")
     */
    private $firstname;

    /**
     * @ORM\Column(name="date_register", type="datetime")
     */
    private $date_register;

    /**
     * @ORM\Column(name="token", type="string", unique=true, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(name="activation_code", type="string", unique=true, nullable=true)
     */
    private $activation_code;

    /**
     * @ORM\Column(name="account_type", type="string", nullable=true)
     */
    private $account_type;

    /**
     * One User has One avatar Image.
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     */
    private $avatar;

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\Capture", mappedBy="user")
     */
    private $captures;

    /**
     * @ORM\Column(name="biography", type="text", nullable=true)
     */
    private $biography;

    public function __construct()
    {
        $this->active = false;
        $this->roles = array("ROLE_USER");
        $this->date_register = new \DateTime('now');
        $this->activation_code = md5(uniqid('code_', false));
    }

    public function getUsername() :string
    {
        return $this->username;
    }

    public function setUsername($username) :void
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getActive() :bool
    {
        return $this->active;
    }

    public function setActive($active) :void
    {
        $this->active = $active;
    }

    public function getRoles() :array
    {
        return $this->roles;
    }

    public function setRole($role) :void
    {
        if (!in_array($this->roles, $role)) {
            $this->roles[] = $role;
        }
    }

    public function addRole($role) :void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole($role) :array
    {
        if (in_array($role, $this->roles)) {
            unset($role);
            return $this->roles;
        }
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getDateRegister()
    {
        return $this->date_register;
    }

    /**
     * @param mixed $date_register
     */
    public function setDateRegister($date_register): void
    {
        $this->date_register = $date_register;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function getActivationCode() :string
    {
        return $this->activation_code;
    }

    public function setActivationCode($activation_code) :void
    {
        $this->activation_code = $activation_code;
    }

    public function getAccountType()
    {
        return $this->account_type;
    }

    public function setAccountType($account_type) :void
    {
        $this->account_type = $account_type;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }/**
 * @return mixed
 */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param mixed $biography
     */
    public function setBiography($biography): void
    {
        $this->biography = $biography;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, array('allowed_classes' => false));
    }
}
