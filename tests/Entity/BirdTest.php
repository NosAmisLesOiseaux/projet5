<?php

namespace App\Tests\EntitiesTests;

use App\Entity\Bird;
use PHPUnit\Framework\TestCase;

class BirdTest extends TestCase
{
    public function setup()
    {
        $this->bird = new Bird();

        $this->bird->setReign('Animalia');
        $this->bird->setPhylum('Chordata');
        $this->bird->setClass('Aves');
        $this->bird->setBirdOrder('Accipitriformes');
        $this->bird->setFamily('Accipitridae');
        $this->bird->setVernacularname('"Epervier bicolore "');
        $this->bird->setValidname('Accipiter bicolor');
    }

    public function testBirdIsInstanceOfBird()
    {
        $this->assertInstanceOf(Bird::class, $this->bird);
    }

    public function testBirdHasReign()
    {
        $this->assertNotNull($this->bird->getReign(), "L'oiseau n'a pas de règne renseigné.");
    }

    public function testBirdHasPhylum()
    {
        $this->assertNotNull($this->bird->getPhylum(), "L'oiseau n'a pas de phylum renseigné.");
    }

    public function testBirdHasClass()
    {
        $this->assertNotNull($this->bird->getClass(), "L'oiseau n'a pas de classe renseigné.");
    }

    public function testBirdHasBirdOrder()
    {
        $this->assertNotNull($this->bird->getBirdOrder(), "L'oiseau n'a pas d'ordre renseigné.");
    }

    public function testBirdHasFamily()
    {
        $this->assertNotNull($this->bird->getFamily(), "L'oiseau n'a pas de famille renseignée.");
    }

    public function testBirdHasVernacularname()
    {
        $this->assertNotNull($this->bird->getVernacularname(), "L'oiseau n'a pas de vernacular name renseigné.");
    }

    public function testBirdHasValidname()
    {
        $this->assertNotNull($this->bird->getValidname(), "L'oiseau n'a pas de valid name renseigné.");
    }
}