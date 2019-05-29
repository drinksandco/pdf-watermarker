<?php

namespace Uvinum\PDFWatermark;


use setasign\Fpdi\Fpdi;

class FpdiPdfWatermarker implements PdfWatermarker
{
    private $watermark;
    private $totalPages;
    private $specificPages = [];
    private $position;
    private $asBackground = false;
    /** @var Fpdi */
    private $fpdi;

    public function __construct(Pdf $file, Watermark $watermark)
    {
        $this->fpdi = new FPDI();
        $this->totalPages = $this->fpdi->setSourceFile($file->getRealPath());
        $this->watermark = $watermark;
        $this->position = new Position('MiddleCenter');
    }

    /**
     * Set page range.
     *
     * @param int $startPage - the first page to be watermarked
     * @param int $endPage - (optional) the last page to be watermarked
     */
    public function setPageRange($startPage = 1, $endPage = null)
    {
        $endPage = is_null($endPage) ? $this->totalPages : $endPage;

        foreach (range($startPage, $endPage) as $pageNumber) {
            $this->specificPages[] = $pageNumber;
        }
    }

    /**
     * Apply the watermark below the PDF's content.
     */
    public function setAsBackground()
    {
        $this->asBackground = true;
    }

    /**
     * Apply the watermark over the PDF's content.
     */
    public function setAsOverlay()
    {
        $this->asBackground = false;
    }

    /**
     * Set the Position of the Watermark
     *
     * @param Position $position
     */
    public function setPosition(Position $position)
    {
        $this->position = $position;
    }

    /**
     * Loop through the pages while applying the watermark.
     */
    private function process()
    {
        foreach (range(1, $this->totalPages) as $pageNumber) {
            $this->importPage($pageNumber);

            if (in_array($pageNumber, $this->specificPages) || empty($this->specificPages)) {
                $this->watermarkPage($pageNumber);
            } else {
                $this->watermarkPage($pageNumber, false);
            }
        }
    }

    /**
     * Import page.
     *
     * @param int $pageNumber - page number
     */
    private function importPage($pageNumber)
    {
        $templateId = $this->fpdi->importPage($pageNumber);
        $templateDimension = $this->fpdi->getTemplateSize($templateId);

        if ($templateDimension['width'] > $templateDimension['height']) {
            $orientation = "L";
        } else {
            $orientation = "P";
        }

        $this->fpdi->addPage($orientation, array($templateDimension['width'], $templateDimension['height']));
    }

    /**
     * Apply the watermark to a specific page.
     *
     * @param int $pageNumber - page number
     * @param bool $watermark_visible - (optional) Make the watermark visible. True by default.
     */
    private function watermarkPage($pageNumber, $watermark_visible = true)
    {
        $templateId = $this->fpdi->importPage($pageNumber);
        $templateDimension = $this->fpdi->getTemplateSize($templateId);

        $wWidth = ($this->watermark->getWidth() / 96) * 25.4; //in mm
        $wHeight = ($this->watermark->getHeight() / 96) * 25.4; //in mm

        $watermarkCoords = $this->calculateWatermarkCoordinates(
            $wWidth,
            $wHeight,
            $templateDimension['width'],
            $templateDimension['height']
        );

        if ($watermark_visible) {
            if ($this->asBackground) {
                $this->fpdi->Image($this->watermark->getFilePath(), $watermarkCoords[0], $watermarkCoords[1], -96);
                $this->fpdi->useTemplate($templateId);
            } else {
                $this->fpdi->useTemplate($templateId);
                $this->fpdi->Image($this->watermark->getFilePath(), $watermarkCoords[0], $watermarkCoords[1], -96);
            }
        } else {
            $this->fpdi->useTemplate($templateId);
        }
    }

    /**
     * Calculate the coordinates of the watermark's position.
     *
     * @param int $wWidth - watermark's width
     * @param int $wHeight - watermark's height
     * @param int $tWidth - page width
     * @param int $Height -page height
     *
     * @return array - coordinates of the watermark's position
     */
    private function calculateWatermarkCoordinates($wWidth, $wHeight, $tWidth, $tHeight)
    {
        switch ($this->position->getName()) {
            case 'TopLeft':
                $x = 0;
                $y = 0;
                break;
            case 'TopCenter':
                $x = ($tWidth - $wWidth) / 2;
                $y = 0;
                break;
            case 'TopRight':
                $x = $tWidth - $wWidth;
                $y = 0;
                break;
            case 'MiddleLeft':
                $x = 0;
                $y = ($tHeight - $wHeight) / 2;
                break;
            case 'MiddleRight':
                $x = $tWidth - $wWidth;
                $y = ($tHeight - $wHeight) / 2;
                break;
            case 'BottomLeft':
                $x = 0;
                $y = $tHeight - $wHeight;
                break;
            case 'BottomCenter':
                $x = ($tWidth - $wWidth) / 2;
                $y = $tHeight - $wHeight;
                break;
            case 'BottomRight':
                $x = $tWidth - $wWidth;
                $y = $tHeight - $wHeight;
                break;
            case 'MiddleCenter':
            default:
                $x = ($tWidth - $wWidth) / 2;
                $y = ($tHeight - $wHeight) / 2;
                break;
        }

        return array($x, $y);
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function savePdf($fileName = 'doc.pdf')
    {
        $this->process();
        $this->fpdi->Output($fileName, 'F');
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function downloadPdf($fileName = 'doc.pdf')
    {
        $this->process();
        $this->fpdi->Output($fileName, 'D');
    }

    /**
     * @param string $fileName
     * @return void
     */
    public function stdOut($fileName = 'doc.pdf')
    {
        $this->process();
        $this->fpdi->Output($fileName, 'I');
    }
}
