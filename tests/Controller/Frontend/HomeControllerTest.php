<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 05/09/18
 * Time: 16:55
 */

namespace App\Tests\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = static::createClient();
    }

    public function testShowHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Nos amis les oiseaux")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Les dernières observations")')->count() > 0);
    }
}
