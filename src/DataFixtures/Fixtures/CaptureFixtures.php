<?php

namespace App\DataFixtures\Fixtures;

use App\Entity\User;
use App\Entity\Capture;
use App\Entity\Bird;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class CaptureFixtures extends Fixture
{
    private $em;
    private $bird;
    private $bird2;
    private $user1;
    private $user2;
    private $user3;
    private $user4;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->bird = $this->em->getRepository(Bird::class)->findOneById('1');
        $this->bird = $this->em->getRepository(Bird::class)->findOneById('545');
        $this->user1 = $this->em->getRepository(User::class)->findOneById('1');
        $this->user2 = $this->em->getRepository(User::class)->findOneById('2');
        $this->user3 = $this->em->getRepository(User::class)->findOneById('3');
        $this->user3 = $this->em->getRepository(User::class)->findOneById('4');
    }

    public function load(ObjectManager $manager)
    {
        $capture1 = new Capture();
        $capture1->setContent('Capture numéro 1 - Epervier bicolore');
        $capture1->setLatitude('48.70981');
        $capture1->setLongitude('2.041563');
        $capture1->setAddress('Chemin Jean Racine');
        $capture1->setZipcode('78460');
        $capture1->setCity('Chevreuse');
        $capture1->setRegion('Ile-de-France');
        $capture1->setStatus('published');
        $capture1->setUser($this->user3);
        $capture1->setBird($this->bird);

        $capture2 = new Capture();
        $capture2->setContent('Capture numéro 2');
        $capture2->setLatitude('48.70981');
        $capture2->setLongitude('2.041563');
        $capture2->setAddress('Chemin Jean Racine');
        $capture2->setZipcode('78460');
        $capture2->setCity('Chevreuse');
        $capture2->setRegion('Ile-de-France');
        $capture2->setStatus('draft');
        $capture2->setUser($this->user3);
        $capture2->setBird($this->bird);

        $capture3 = new Capture();
        $capture3->setContent('Capture numéro 3');
        $capture3->setLatitude('48.70981');
        $capture3->setLongitude('2.041563');
        $capture3->setAddress('Chemin Jean Racine');
        $capture3->setZipcode('78460');
        $capture3->setCity('Chevreuse');
        $capture3->setRegion('Ile-de-France');
        $capture3->setStatus('waiting_for_validation');
        $capture3->setUser($this->user1);
        $capture3->setBird($this->bird);

        $capture4 = new Capture();
        $capture4->setContent('Capture numéro 4');
        $capture4->setLatitude('48.70981');
        $capture4->setLongitude('2.041563');
        $capture4->setAddress('Chemin Jean Racine');
        $capture4->setZipcode('78460');
        $capture4->setCity('Chevreuse');
        $capture3->setRegion('Ile-de-France');
        $capture4->setStatus('validated');
        $capture4->setUser($this->user2);
        $capture4->setBird($this->bird);
        $capture4->setValidatedBy($this->user3);

        $capture5 = new Capture();
        $capture5->setContent('Nouvelle observation d\'un gallicolombe de Stair');
        $capture5->setLatitude('48.804865');
        $capture5->setLongitude('2.120355');
        $capture5->setAddress('Place d\'Armes');
        $capture5->setZipcode('78000');
        $capture5->setCity('Versailles');
        $capture5->setRegion('Ile-de-France');
        $capture5->setStatus('draft');
        $capture5->setUser($this->user3);
        $capture5->setBird($this->bird2);

        $capture6 = new Capture();
        $capture6->setContent('Nouvelle observation d\'un gallicolombe de Stair');
        $capture6->setLatitude('48.804865');
        $capture6->setLongitude('2.120355');
        $capture6->setAddress('Place d\'Armes');
        $capture6->setZipcode('78000');
        $capture6->setCity('Versailles');
        $capture6->setRegion('Ile-de-France');
        $capture6->setStatus('published');
        $capture6->setUser($this->user3);
        $capture6->setBird($this->bird2);

        $capture7 = new Capture();
        $capture7->setContent('Nouvelle observation');
        $capture7->setLatitude('43.6396978');
        $capture7->setLongitude('5.0945406');
        $capture7->setAddress('Boulevard du maréchal foch');
        $capture7->setZipcode('13300');
        $capture7->setCity('Salon-de-Provence');
        $capture7->setRegion('Provence-Alpes-Côte d\'Azur');
        $capture7->setStatus('waiting_for_validation');
        $capture7->setUser($this->user2);
        $capture7->setBird($this->bird1);

        $manager->persist($capture1);
        $manager->persist($capture2);
        $manager->persist($capture3);
        $manager->persist($capture4);

        $manager->flush();
    }
}