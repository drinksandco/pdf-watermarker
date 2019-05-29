<?php

declare(strict_types=1);

namespace Uvinum\PDFWatermark;

use InvalidArgumentException;
use RuntimeException;
use SplFileObject;

use function exif_imagetype;
use function getimagesize;
use function imagecreatefromjpeg;
use function imagecreatefrompng;
use function imagedestroy;
use function imageinterlace;
use function imagejpeg;
use function imagepng;
use function imagesavealpha;
use function sys_get_temp_dir;
use function uniqid;

class Watermark extends SplFileObject
{

    private const INVALID_FILE = 'Unsupported image type: %s';
    private $height;
    private $width;
    private $tmpFile;

    public function __construct(string $file)
    {
        parent::__construct($file);
        $imageType = exif_imagetype($file);
        $this->tmpFile = sys_get_temp_dir() . '/' . uniqid() . '.png';

        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $this->prepareJpgFile($file);
                break;

            case IMAGETYPE_PNG:
                $this->preparePngFile($file);
                break;
            default:
                throw new InvalidArgumentException(sprintf(self::INVALID_FILE, $imageType));
                break;
        };

        $size = getimagesize($this->tmpFile);
        $this->width = $size[0];
        $this->height = $size[1];
    }

    /**
     * Return the path to the tmp file
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->tmpFile;
    }

    /**
     * Returns the watermark's height
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Returns the watermark's width
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    private function prepareJpgFile(string $file): void
    {
        if (false === $image = imagecreatefromjpeg($file)) {
            throw new RuntimeException('Error occurred creating image from Jpg file.');
        }
        imageinterlace($image, 0);
        imagejpeg($image, $this->tmpFile);
        imagedestroy($image);
    }

    private function preparePngFile(string $file): void
    {
        if (false === $image = imagecreatefrompng($file)) {
            throw new RuntimeException('Error occurred creating image from Png file.');
        }
        imageinterlace($image, 0);
        imagesavealpha($image, true);
        imagepng($image, $this->tmpFile);
        imagedestroy($image);
    }
}
