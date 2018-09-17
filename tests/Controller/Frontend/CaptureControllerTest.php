<?php

// tests/ControllersTests/Frontend/CaptureControllerTest.php

namespace App\tests\ControllersTests\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\User;
use App\Entity\Comment;

class CaptureControllerTest extends WebTestCase
{
    private $entityManager;
    private $user;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->user = $this->entityManager->getRepository(User::class)->findOneById('3');
    }

    public function testShowCapture()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/observation/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains(\'"Epervier bicolore "\')')->count() > 0);
    }

    public function testShowCaptures()
    {
        $client = static::createClient();

        $client->request('GET', '/observations');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowCapturesPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/observations/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}