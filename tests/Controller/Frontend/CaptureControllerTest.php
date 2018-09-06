<?php

// tests/Controller/Frontend/CaptureControllerTest.php

namespace App\tests\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\User;
use App\Entity\Comment;

class CaptureControllerTest extends WebTestCase
{
    private $entityManager;
    private $user;
    private $client;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->user = $this->entityManager->getRepository(User::class)->findOneById('3');
        $this->client = static::createClient();
    }

    public function testShowCapture()
    {
        $crawler = $this->client->request('GET', '/observation/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains(\'"Epervier bicolore "\')')->count() > 0);
    }

    public function testShowCaptures()
    {
        $this->client->request('GET', '/observations');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShowCapturesPageTwo()
    {
        $this->client->request('GET', '/observations/2');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}