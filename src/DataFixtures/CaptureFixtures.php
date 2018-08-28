<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 14/08/18
 * Time: 15:27
 */

namespace App\DataFixtures;


use App\Entity\Bird;
use App\Entity\Capture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class CaptureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 10; $i++) {
            $capture = new Capture();
            $capture->setAddress("Adresse $i");
            $bird = $this->getReference('bird'.rand(1,5));
            $capture->setBird($bird);
            $capture->setCity("City $i");
            $capture->setComplement("Complement $i");
            $capture->setContent("Content $i");
            $capture->setCreatedDate(new \DateTime('now'));
            $capture->setLatitude('0.5');
            $capture->setLongitude('0.7');
            $capture->setNaturalistComment("Naturalist Comment");
            $capture->setRegion("Region $i");
            $capture->setStatus(1);
            $user = $this->getReference('user'.rand(1,5));
            $capture->setUser($user);
            //$capture->setValidatedBy($user);
            $capture->setZipcode("74 00$i");
            $capture->setImage(null);
            $manager->persist($capture);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            BirdFixtures::class,
            UserFixtures::class
        );
    }


}
