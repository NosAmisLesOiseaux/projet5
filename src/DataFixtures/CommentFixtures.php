<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Capture;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class CommentFixtures extends Fixture
{
    private $em;
    private $capture1;
    private $capture2;
    private $user1;
    private $user2;
    private $user3;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->capture1 = $this->em->getRepository(Capture::class)->findOneById('1');
        $this->capture4 = $this->em->getRepository(Capture::class)->findOneById('4');
        $this->user1 = $this->em->getRepository(User::class)->findOneById('1');
        $this->user2 = $this->em->getRepository(User::class)->findOneById('2');
        $this->user3 = $this->em->getRepository(User::class)->findOneById('3');
    }

    public function load(ObjectManager $manager)
    {
        $comment1 = new Comment();
        $comment1->setAuthor($this->user1);
        $comment1->setContent('Belle découverte !');
        $comment1->setCreatedAt(new \DateTime('now'));
        $comment1->setPublished(true);
        $comment1->setCapture($this->capture1);

        $comment2 = new Comment();
        $comment2->setAuthor($this->user2);
        $comment2->setContent('Je pensais pas trouver cette espèce dans cette région !');
        $comment2->setCreatedAt(new \DateTime('now'));
        $comment2->setPublished(false);
        $comment1->setCapture($this->capture1);

        $comment3 = new Comment();
        $comment3->setAuthor($this->user3);
        $comment3->setContent('Très professionnel cette observation.');
        $comment3->setCreatedAt(new \DateTime('now'));
        $comment3->setPublished(true);
        $comment1->setCapture($this->capture4);

        $manager->persist($comment1);
        $manager->persist($comment2);
        $manager->persist($comment3);

        $manager->flush();
    }
}