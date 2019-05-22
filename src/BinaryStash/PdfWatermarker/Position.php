<?php
declare(strict_types=1);

namespace BinaryStash\PdfWatermarker;

use InvalidArgumentException;
use function in_array;

class Position
{
    private const TOP_LEFT = 'TopLeft';
    private const TOP_CENTER = 'TopCenter';
    private const TOP_RIGHT = 'TopRight';
    private const MIDDLE_LEFT = 'MiddleLeft';
    private const MIDDLE_CENTER = 'MiddleCenter';
    private const MIDDLE_RIGHT = 'MiddleRight';
    private const BOTTOM_LEFT = 'BottomLeft';
    private const BOTTOM_CENTER = 'BottomCenter';
    private const BOTTOM_RIGHT = 'BottomRight';

    /** @var string */
    private $name;

    private const VALID_POSITIONS = [
        self::TOP_LEFT,
        self::TOP_CENTER,
        self::TOP_RIGHT,
        self::MIDDLE_LEFT,
        self::MIDDLE_CENTER,
        self::MIDDLE_RIGHT,
        self::BOTTOM_LEFT,
        self::BOTTOM_CENTER,
        self::BOTTOM_RIGHT
    ];

    private function __construct(string $name)
    {
        if (!in_array($name, self::VALID_POSITIONS, true))
        {
            throw new InvalidArgumentException('Unsupported position:' . $name);
        }

        $this->name = $name;
    }

    /**
     *
     * @param string $position
     *
     * @return Position
     */
    public static function fromName(string $position): self
    {
        return new self($position);
    }

    public function name(): string
    {
        return $this->name;
    }
}
