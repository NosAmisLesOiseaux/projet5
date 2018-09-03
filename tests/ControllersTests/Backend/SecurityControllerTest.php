<?php
/**
 * Created by PhpStorm.
 * User: seymos
 * Date: 03/09/18
 * Time: 17:19
 */

namespace App\Tests\ControllersTests\Backend;


use FOS\RestBundle\Tests\Functional\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client;
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = static::createClient();
    }

    public function testLogin()
    {
        $crawler = $this->client->request('GET',"/login");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('submit')->form();
        $form['username'] = "admin@nao.fr";
        $form['password'] = "admin";
        $crawler = $this->client->submit($form);
        $values = $form->getPhpValues();
        $this->assertEquals("admin@nao.fr", $values['username']);
        $this->assertEquals("admin", $values['password']);
    }
}
