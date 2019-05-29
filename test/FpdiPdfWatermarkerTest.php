<?php

declare(strict_types=1);

namespace UvinumTest\PDFWatermark;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Uvinum\PDFWatermark\FpdiPdfWatermarker;
use Uvinum\PDFWatermark\Pdf;
use Uvinum\PDFWatermark\PdfWatermarker;
use Uvinum\PDFWatermark\Watermark;

class FpdiPdfWatermarkerTest extends TestCase
{
    /** @var MockObject|Pdf */
    private $pdf;
    /** @var MockObject|Watermark */
    private $watermark;
    /** @var FpdiPdfWatermarker */
    private $waterMarker;

    public function testItShouldBeCreatedWithAPdfAndAWatermarkImage(): void
    {
        $this->givenAPdf();
        $this->givenAWatermark();
        $this->whenWaterMarkerIsCreated();
        $this->thenItShouldBeValidWaterMarker();
    }

    public function testItShouldSaveNewWaterMarkedPdf(): void
    {
        $this->givenAPdf();
        $this->givenAWatermark();
        $this->whenWaterMarkerIsSavedAsPdf();
        $this->thenWaterMarkedFileShouldExistInFilesystem();
    }

    private function givenAPdf(): void
    {
        $this->pdf = new Pdf('test/test.pdf');
    }

    private function givenAWatermark(): void
    {
        $this->watermark = new Watermark('test/test.png');
    }

    private function whenWaterMarkerIsCreated(): void
    {
        $this->waterMarker = new FpdiPdfWatermarker($this->pdf, $this->watermark);
    }

    private function thenItShouldBeValidWaterMarker(): void
    {
        $this->assertInstanceOf(PdfWatermarker::class, $this->waterMarker);
    }

    private function whenWaterMarkerIsSavedAsPdf(): void
    {
        $this->whenWaterMarkerIsCreated();
        $this->waterMarker->savePdf('test/test-marked.pdf');
    }

    private function thenWaterMarkedFileShouldExistInFilesystem(): void
    {
        $this->assertFileExists('test/test-marked.pdf');
        unlink('test/test-marked.pdf');
    }
}
