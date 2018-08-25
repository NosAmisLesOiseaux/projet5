<?php

// tests/ControllersTests/Frontend/HomeControllerTest.php

namespace App\tests\ControllersTests\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;

class HomeControllerTest extends WebTestCase
{
    public function testShowHomePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Nos amis les oiseaux")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Les dernières observations")')->count() > 0);
    }
}