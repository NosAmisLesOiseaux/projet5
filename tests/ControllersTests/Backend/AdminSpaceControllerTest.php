<?php

// tests/ControllersTests/Backend/AdminSpaceControllerTest.php

namespace App\tests\ControllersTests\Backend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminSpaceControllerTest extends WebTestCase
{
    public function testShowAdminSpace()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace administration")')->count() > 0);
    }

    public function testShowNextPublishedCaptures()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-publiees');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Observations publiées")')->count() > 0);
    }

    public function testShowNextPublishedCapturesPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-publiees/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Observations publiées")')->count() > 0);
    }

    public function testShowNextWaitingCaptures()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-en-attente');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Observations en attente de validation")')->count() > 0);
    }

    public function testShowNextWaintingCapturesPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/observations-en-attente/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Observations en attente de validation")')->count() > 0);
    }

    public function testShowNexPublishedComments()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-publies');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Commentaires publiés")')->count() > 0);
    }

    public function testShowNextPublishedCommentsPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-publies/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Commentaires publiés")')->count() > 0);
    }

    public function testShowNexReportedComments()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-signales');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Commentaires signalés")')->count() > 0);
    }

    public function testShowNextReportedCommentsPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/espace-administration/commentaires-signales/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('h1:contains("Espace d\'administration")')->count() > 0);
        $this->assertTrue($crawler->filter('h2:contains("Commentaires signalés")')->count() > 0);
    }

    public function testIgnoreReportedComment()
    {
        $client = static::createClient();

        $client->request('GET', '/ignorer-commentaire-signale/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRemoveComment()
    {
        $client = static::createClient();

        $client->request('GET', '/supprimer-commentaire/4');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}