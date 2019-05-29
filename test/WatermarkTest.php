<?php

declare(strict_types=1);

namespace UvinumTest\PDFWatermark;

use PHPUnit\Framework\TestCase;
use SplFileObject;
use Uvinum\PDFWatermark\Watermark;

class WatermarkTest extends TestCase
{
    private const PNG_FILE_PATH = 'test/test.png';
    private const JPG_FILE_PATH = 'test/test.jpg';
    private const INVALID_FILE_PATH = 'test/test.pdf';
    /** @var string */
    private $filePath;
    /** @var Watermark */
    private $watermark;

    public function testItShouldThrowAnExceptionWhenFileIsNotAValidPngOrJpg(): void
    {
        $this->expectsInvalidArgumentException();
        $this->givenNonPngOrJpgFile();
        $this->whenWatermarkIsCreated();
    }

    public function testItShouldBeAValidPngFile(): void
    {
        $this->givenAPngFilePath();
        $this->whenWatermarkIsCreated();
        $this->thenItShouldBeAValidWatermarkFile();
    }

    public function testItShouldBeAValidJpgFile(): void
    {
        $this->givenAJpgFilePath();
        $this->whenWatermarkIsCreated();
        $this->thenItShouldBeAValidWatermarkFile();
    }

    private function givenAPngFilePath(): void
    {
        $this->filePath = self::PNG_FILE_PATH;
    }

    private function whenWatermarkIsCreated(): void
    {
        $this->watermark = new Watermark($this->filePath);
    }

    private function thenItShouldBeAValidWatermarkFile(): void
    {
        $this->assertInstanceOf(SplFileObject::class, $this->watermark);
    }

    private function givenAJpgFilePath(): void
    {
        $this->filePath = self::JPG_FILE_PATH;
    }

    private function givenNonPngOrJpgFile(): void
    {
        $this->filePath = self::INVALID_FILE_PATH;
    }

    private function expectsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
    }
}
