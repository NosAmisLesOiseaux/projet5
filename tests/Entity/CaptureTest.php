<?php

namespace App\Tests\Entity;


use App\Entity\Capture;
use PHPUnit\Framework\TestCase;

class CaptureTest extends TestCase
{
    private $capture;

    public function setup()
    {
        $this->capture = new Capture();
    }

    public function testCaptureIsInstanceOfCapture()
    {
        $this->assertInstanceOf(Capture::class, $this->capture, "Capture n'est pas une instance de Capture");
    }
}
