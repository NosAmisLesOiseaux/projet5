<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $user1 = new user();
        $user1->setUsername('username1');
        $password1 = $this->encoder->encodePassword($user1, 'pass_1234');
        $user1->setPassword($password1);
        $user1->setEmail('user1@test.fr');
        $user1->setLastname('lastname1');
        $user1->setFirstname('firstname1');
        $role1 = [];
        $role1[] = 'particular';
        $user1->setRoles($role1);

        $user2 = new user();
        $user2->setUsername('username2');
        $password2 = $this->encoder->encodePassword($user2, 'pass_5678');
        $user2->setPassword($password2);
        $user2->setEmail('user2@test.fr');
        $user2->setLastname('lastname2');
        $user2->setFirstname('firstname2');
        $role2 = [];
        $role2[] = 'particular';
        $user2->setRoles($role2);

        $user3 = new user();
        $user3->setUsername('username3');
        $password3 = $this->encoder->encodePassword($user3, 'pass_9999');
        $user3->setPassword($password3);
        $user3->setEmail('user3@test.fr');
        $user3->setLastname('lastname3');
        $user3->setFirstname('firstname3');
        $role3 = [];
        $role3[] = 'naturalist';
        $user3->setRoles($role3);

        $user4 = new user();
        $user4->setUsername('username4');
        $password4 = $this->encoder->encodePassword($user4, 'pass_1111');
        $user4->setPassword($password4);
        $user4->setEmail('user4@test.fr');
        $user4->setLastname('lastname4');
        $user4->setFirstname('firstname4');
        $role4 = [];
        $role4[] = 'administrator';
        $user4->setRoles($role4);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);

        $manager->flush();
    }
}