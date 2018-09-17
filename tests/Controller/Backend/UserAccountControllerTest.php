<?php

// tests/ControllersTests/Backend/UserAccountControllerTest.php

namespace App\tests\ControllersTests\Backend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserAccountControllerTest extends WebTestCase
{
    public function testshowUserAccount()
    {
        $client = static::createClient();

        $client->request('GET', '/mon-compte');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowUserAccountPageTwo()
    {
        $client = static::createClient();

        $client->request('GET', '/mon-compte/2');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}