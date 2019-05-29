<?php

declare(strict_types=1);

namespace UvinumTest\PDFWatermark;

use PHPUnit\Framework\TestCase;
use SplFileObject;
use Uvinum\PDFWatermark\Pdf;

class PdfTest extends TestCase
{
    private const FILE_PATH = 'test/test.pdf';
    private const TXT_FILE_PATH = 'test/test.txt';
    /** @var string */
    private $filePath;
    /** @var Pdf */
    private $pdf;

    public function testItShouldThrowAnExceptionWhenFileIsNotAValidPdf(): void
    {
        $this->expectsInvalidArgumentException();
        $this->givenANonPdfFilePath();
        $this->whenPdfIsCreated();
    }

    public function testItShouldBeValidPdf(): void
    {
        $this->givenAPdfFilePath();
        $this->whenPdfIsCreated();
        $this->thenItShouldBeValidPdfFile();
    }

    private function givenAPdfFilePath(): void
    {
        $this->filePath = self::FILE_PATH;
    }

    private function whenPdfIsCreated(): void
    {
        $this->pdf = new Pdf($this->filePath);
    }

    private function thenItShouldBeValidPdfFile(): void
    {
        $this->assertInstanceOf(SplFileObject::class, $this->pdf);
    }

    private function expectsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
    }

    private function givenANonPdfFilePath(): void
    {
        $this->filePath = self::TXT_FILE_PATH;
    }
}
