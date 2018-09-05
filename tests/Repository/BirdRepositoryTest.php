<?php

namespace App\Tests\Repository;


use App\Entity\Bird;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BirdRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param $letter
     * @param $elementsPerPage
     * @param $firstEntrance
     */
    public function testGetBirdsByFirstLetter($letter, $elementsPerPage, $firstEntrance)
    {
        $birds = $this->getRepository()->getBirdsByFirstLetter($letter, $elementsPerPage, $firstEntrance);
        $this->assertGreaterThanOrEqual(1, count($birds), "Aucun oiseau n'a été récupéré pour cette lettre.");
    }

    public function testGetBirdsPerPage($elementsPerPage, $firstEntrance)
    {
        $birds = $this->getRepository()->getBirdsPerPage($elementsPerPage, $firstEntrance);
        $this->assertGreaterThanOrEqual(1, count($birds), "Aucun oiseau n'a été trouvé pour cette page.");
    }

    public function testGetBirdsByOrderAsc()
    {
        $birds = $this->getRepository()->getBirdsByOrderAsc();
        $this->assertGreaterThanOrEqual(1, count($birds), "Aucun oiseau n'a été trouvé.");
    }

    public function testCountBirds()
    {
        $birds = $this->getRepository()->countBirds();
    }

    public function testCountBirdsByLetter($letter)
    {
        $birds = $this->getRepository()->countBirdsByLetter();
    }

    public function testCountSearchBirdsByRegion($region, $draftStatus, $waitingStatus)
    {
        $result = $this->getRepository()->countSearchBirdsByRegion($region, $draftStatus, $waitingStatus);
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository(Bird::class);
    }
}
