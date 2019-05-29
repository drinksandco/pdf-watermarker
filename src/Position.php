<?php

declare(strict_types=1);

namespace Uvinum\PDFWatermark;

use InvalidArgumentException;

use function array_key_exists;
use function array_flip;

class Position
{
    private const POSITIONS = [
        'TopLeft',
        'TopCenter',
        'TopRight',
        'MiddleLeft',
        'MiddleCenter',
        'MiddleRight',
        'BottomLeft',
        'BottomCenter',
        'BottomRight',
    ];
    private const UNSUPPORTED_POSITION = 'Unsupported position: %s';
    private $name;

    /**
     * @param $name
     *
     * @throws Exception
     */
    public function __construct(string $name)
    {
        if (false === array_key_exists($name, array_flip(self::POSITIONS))) {
            throw new InvalidArgumentException(sprintf(self::UNSUPPORTED_POSITION, $name));
        }

        $this->name = $name;
    }

    /**
     * @return string name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Position $position
     *
     * @return bool
     */
    public function equals(Position $position): bool
    {
        return ($this->name === $position->getName());
    }

    /**
     * @return string name
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Position
     * @throws InvalidArgumentException
     * @deprecated It will be removed on version(We don't like magic ;-D)
     */
    public static function __callStatic($name, $arguments): self
    {
        return new self($name);
    }
}
