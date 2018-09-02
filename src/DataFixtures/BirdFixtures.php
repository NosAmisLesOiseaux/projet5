<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 14/08/18
 * Time: 14:18
 */

namespace App\DataFixtures;


use App\Entity\Bird;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BirdFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $bird = new Bird();
            $bird->setBirdOrder("Order$i");
            $bird->setClass("Class$i");
            $bird->setFamily("Family$i");
            $bird->setPhylum("Phylum$i");
            $bird->setReign("Reign$i");
            $bird->setValidname("Validname$i");
            $bird->setVernacularname("Vernacularname$i");
            $this->addReference('bird'.$i, $bird);
            $manager->persist($bird);
        }
        $manager->flush();
    }
}
