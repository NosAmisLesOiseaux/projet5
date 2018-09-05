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

    private $letter;
    private $elementsPerPage; 
    private $firstEntrance;
    private $region;
    private $naoCaptureManager; 
    private $draftStatus;
    private $waitingStatus;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->letter = 'a';
        $this->elementsPerPage = 15;
        $this->firstEntrance = 0;
        $this->region = 'Ile-de-France';
    }

    /**
     * @param $letter
     * @param $elementsPerPage
     * @param $firstEntrance
     */
    public function testGetBirdsByFirstLetter()
    {
        $birds = $this->getRepository()->getBirdsByFirstLetter($this->letter, $this->elementsPerPage, $this->firstEntrance);
        $this->assertGreaterThanOrEqual(1, count($birds), "Aucun oiseau n'a été récupéré pour cette lettre.");
    }

    public function testGetBirdsPerPage()
    {
        $birds = $this->getRepository()->getBirdsPerPage($this->elementsPerPage, $this->firstEntrance);
        $this->assertGreaterThanOrEqual(15, count($birds), "Aucun oiseau n'a été trouvé pour cette page.");
    }

    public function testCountBirds()
    {
        $birds = $this->getRepository()->countBirds();

        $this->assertEquals(3091, $numberOfBirds);
    }

    public function testGetBirdsByOrderAsc()
    {
        $birds = $this->getRepository()->getBirdsByOrderAsc();
        $this->assertGreaterThanOrEqual(1, count($birds), "Aucun oiseau n'a été trouvé.");
    }

    public function getRepository()
    {
        return $this->entityManager->getRepository(Bird::class);
    }
}
