<?php

namespace App\Tests\Entity;

use App\Entity\Capture;
use App\Entity\User;
use App\Entity\Bird;
use PHPUnit\Framework\TestCase;

class CaptureTest extends TestCase
{
    private $user;
    private $validator;
    private $bird;

    public function setup()
    {
        $this->user = new User();
        $this->validator = new User();
        $this->bird = new Bird();

        $this->capture = new Capture();
        $this->capture->setContent('Observation 1');
        $this->capture->setLatitude('48.70981');
        $this->capture->setLongitude('2.041563');
        $this->capture->setAddress('Chemin Jean Racine');
        $this->capture->setRegion('Ile-de-France');
        $this->capture->setZipcode('78460');
        $this->capture->setCity('Chevreuse');
        $this->capture->setStatus('Validated');
        $this->capture->setNaturalistComment('Observation validée');
        $this->capture->setBird($this->bird);
        $this->capture->setUser($this->user);
        $this->capture->setValidatedBy($this->validator);
    }

    public function testCaptureIsInstanceOfCapture()
    {
        $this->assertInstanceOf(Capture::class, $this->capture);
    }

    public function testCaptureHasContent()
    {
        $this->assertNotNull($this->capture->getContent(), "L'observation n'a pas de contenu.");
    }

    public function testCaptureHasLatitude()
    {
        $this->assertNotNull($this->capture->getLatitude(), "L'observation n'a pas de latitude.");
    }

    public function testCaptureHasLongitude()
    {
        $this->assertNotNull($this->capture->getLongitude(), "L'observation n'a pas de longitude.");
    }

    public function testCaptureHasAddress()
    {
        $this->assertNotNull($this->capture->getAddress(), "L'observation n'a pas d'adresse.");
    }

    public function testCaptureHasRegion()
    {
        $this->assertNotNull($this->capture->getRegion(), "L'observation n'a pas de région.");
    }

    public function testCaptureHasZipcode()
    {
        $this->assertNotNull($this->capture->getZipcode(), "L'observation n'a pas de code postal.");
    }

    public function testCaptureHasCity()
    {
        $this->assertNotNull($this->capture->getCity(), "L'observation n'a pas de ville.");
    }

    public function testCaptureHasStatus()
    {
        $this->assertEquals($this->capture->getStatus(), "Validated");
    }

    public function testCaptureHasNaturalistcomment()
    {
        $this->assertNotNull($this->capture->getNaturalistComment(), "L'observation n'a pas de commentaire d'un naturaliste.");
    }

    public function testCaptureHasAuthor()
    {
        $this->assertNotNull($this->capture->getUser(), "L'observation 'n'a pas d'auteur.");
    }

    public function testCaptureHasValidator()
    {
        $this->assertNotNull($this->capture->getValidatedBy(), "L'observation 'n'a pas validateur.");
    }
}