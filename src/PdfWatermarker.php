<?php

declare(strict_types=1);

namespace Uvinum\PDFWatermark;

interface PdfWatermarker
{
    /**
     * Set page range.
     *
     * @param int $start $end the first page to be watermarked
     * @param int|null (optional) the last page to be watermarked
     * @return void
     */
    public function setPageRange(int $start, ?int $end = null): void;

    /**
     * Set the Position of the Watermark
     *
     * @param Position $position
     * @return void
     */
    public function setPosition(Position $position): void;

    /**
     * Set the watermark as background.
     *
     * @return void
     */
    public function setAsBackground(): void;

    /**
     * Set the watermark as overlay.
     *
     * @return void
     */
    public function setAsOverlay(): void;

    /**
     * Save the PDF.
     *
     * @param $file
     * @return void
     */
    public function savePdf($file): void;
}
