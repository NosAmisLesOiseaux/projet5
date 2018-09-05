<?php

namespace App\Tests\Entity;


use App\Entity\Bird;
use PHPUnit\Framework\TestCase;

class BirdTest extends TestCase
{
    private $bird;

    public function setUp()
    {
        $this->bird = new Bird();
    }

    public function testBirdIsInstanceOfBird()
    {
        $this->assertInstanceOf(Bird::class, $this->bird, "Bird n'est pas une instance de Bird.");
    }

    public function testBirdReignIsNotNullAndIsCorrect()
    {
        $this->assertNotNull($this->bird->getReign(), "Le règne de l'oiseau est null.");
        $this->assertEquals("Animalia", $this->bird->getReign(), "Le règne de l'oiseau est incorrect");
    }

    public function testBirdPhylumIsNotNullAndIsCorrect()
    {
        $this->assertNotNull($this->bird->getPhylum(), "Le phylum de l'oiseau est null.");
        $this->assertEquals("Chordata", $this->bird->getPhylum());
    }

    public function testBirdClassIsNotNullAndIsCorrect()
    {
        $this->assertNotNull($this->bird->getClass(), "La classe de l'oiseau est null.");
        $this->assertEquals("Aves", $this->bird->getClass());
    }

    public function testBirdOrderIsNotNullAndIsString()
    {
        $this->bird->setBirdOrder("Accipitriformes");
        $this->assertNotNull($this->bird->getBirdOrder(), "L'ordre de l'oiseau est null.");
        $string_regex = "#^[A-Z]{1}[a-z]{1,}$#";
        $this->assertRegExp($string_regex, $this->bird->getBirdOrder(), "Le format de l'ordre de l'oiseau est incorrect.");
    }

    public function testBirdCdNameIsNotNullAndIsInteger()
    {
        $this->bird->setCdName(441604);
        $this->assertNotNull($this->bird->getCdName(), "Le cd_name de l'oiseau est null.");
        $integer_regex = "#^\d{2,8}$#";
        $this->assertRegExp($integer_regex, $this->bird->getCdName(), "Le format du cd_name de l'oiseau est incorrect.");
    }

    public function testBirdImageUrlAndImageThumbnailUrlAreNotNullAndAreFromTaxref()
    {
        $this->bird->setImageUrl("https://taxref.mnhn.fr/api/media/download/thumbnail/136638");
        $this->bird->setImageThumbnail("https://taxref.mnhn.fr/api/media/download/thumbnail/136638");
        $this->assertNotNull($this->bird->getImageUrl(), "L'image de l'oiseau est null.");
        $this->assertNotNull($this->bird->getImageThumbnail(), "Le thumbnail de l'oiseau est null.");
        $taxref_regex = "#^(https://taxref.mnhn.fr/)#";
        $this->assertRegExp($taxref_regex, $this->bird->getImageUrl(), "L'image de l'oiseau est incorrecte et ne provient pas d'une source sûre.");
        $this->assertRegExp($taxref_regex, $this->bird->getImageThumbnail(), "Le thumbnail de l'oiseau est incorrect et ne provient pas d'une source sûre.");
    }
}
