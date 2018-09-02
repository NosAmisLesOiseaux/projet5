<?php

// tests/ServicesTests/Map/NAOShowMapTest.php

namespace App\Tests\ServicesTests\Map;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Capture;
use App\Entity\Bird;
use App\Services\Map\NAOShowMap;
use App\Services\Capture\NAOCaptureManager;

class NAOShowMapTest extends WebTestCase
{
	private $captures;
	private $capture;
    private $naoShowMap;
    private $naoCaptureManager;
    private $bird;
    private $birdCaptures;
    private $lastCaptures;
    private $client;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

        $this->naoShowMap = $kernel->getContainer()->get('app.nao_show_map');
        $this->naoCaptureManager = $kernel->getContainer()->get('app.nao_capture_manager');
        $this->captures = $this->naoCaptureManager->getPublishedCaptures();
        $this->capture = $this->entityManager->getRepository(Capture::class)->findOneById('1');
        $this->bird = $this->entityManager->getRepository(Bird::class)->findOneById('1');
        $this->birdCaptures = $this->naoCaptureManager->getBirdPublishedCaptures($this->bird);
        $this->lastCaptures = $this->entityManager->getRepository(Capture::class)->getLastPublishedCaptures('4');

        $this->client = static::createClient();
    }

	public function testFormatPublishedCaptures()
	{
		$jsonPublishedCaptures = $this->naoShowMap->formatPublishedCaptures($this->captures);
		
		$this->client->request('GET', '/api/publishedcaptures', array('content' => $jsonPublishedCaptures));
		$response = $this->client->getResponse();
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertSame('application/json', $response->headers->get('Content-Type'));
		$this->assertNotEmpty($this->client->getResponse()->getContent());
	}

	public function testFormatCapture()
	{
		$jsonPublishedCapture = $this->naoShowMap->formatCapture($this->capture);
		
		$this->client->request('GET', '/api/latloncapture/1', array('content' => $jsonPublishedCapture));
		$response = $this->client->getResponse();
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertSame('application/json', $response->headers->get('Content-Type'));
		$this->assertEquals('[{"id":1,"latitude":48.71,"longitude":2.042}]', $response->getContent());
		$this->assertNotEmpty($this->client->getResponse()->getContent());
	}

	public function testFormatBirdPublishedCaptures()
    {
        $jsonBirdPublishedCapture = $this->naoShowMap->formatPublishedCaptures($this->birdCaptures);

        $this->client->request('GET', '/api/birdpublishedcaptures/1', array('content' => $jsonBirdPublishedCapture));
		$response = $this->client->getResponse();
		$this->assertSame(200, $this->client->getResponse()->getStatusCode());
		$this->assertSame('application/json', $response->headers->get('Content-Type'));
		$this->assertNotEmpty($this->client->getResponse()->getContent());
    }

    public function testGetFormatedLastCpatures()
    {
        $jsonLastPublishedCapture = $this->naoShowMap->formatPublishedCaptures($this->lastCaptures);

        $this->client->request('GET', '/api/lastcaptures', array('content' => $jsonLastPublishedCapture));
        $response = $this->client->getResponse();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
        $this->assertNotEmpty($this->client->getResponse()->getContent());
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