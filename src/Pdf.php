<?php

declare(strict_types=1);

namespace Uvinum\PDFWatermark;

use finfo;
use InvalidArgumentException;
use SplFileObject;

use const FILEINFO_MIME_TYPE;

use function in_array;
use function sprintf;

class Pdf extends SplFileObject
{
    private const PDF_MIME_TYPES = [
        'application/pdf',
        'application/octet-stream',
    ];
    private const INVALID_FILE = 'File does not seem to be a PDF: %s';

    public function __construct(string $filepath)
    {
        $fileInfo = new finfo();
        $mimeType = $fileInfo->file($filepath, FILEINFO_MIME_TYPE);

        if (false === in_array($mimeType, self::PDF_MIME_TYPES, true)) {
            throw new InvalidArgumentException(sprintf(self::INVALID_FILE, $mimeType));
        }

        parent::__construct($filepath);
    }
}
