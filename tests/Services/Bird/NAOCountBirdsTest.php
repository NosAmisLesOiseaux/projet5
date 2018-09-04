<?php

// tests/ServicesTests/Bird/NAOCountBirdsTest.php

namespace App\Tests\Services\Bird;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Bird;
use App\Services\Bird\NAOCountBirds;

class NAOCountBirdsTest extends WebTestCase
{
    private $region;
    private $naoCountBirds;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->region = 'Ile-de-France';
        $this->naoCountBirds = $kernel->getContainer()->get('app.nao_count_birds');
    }

    public function testCountBirds()
    {
        $numberOfBirds = $this->naoCountBirds->countBirds();

        $this->assertEquals(3091,  $numberOfBirds);
    }

    public function testCountSearchBirdsByRegion()
    {
        $numberBirdsIDF = $this->naoCountBirds->countSearchBirdsByRegion($this->region);

         $this->assertEquals(3, $numberBirdsIDF);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; 
    }
}