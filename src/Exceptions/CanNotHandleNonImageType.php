<?php

namespace Space\Image\Exceptions;

use InvalidArgumentException as BaseInvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Throwable;

class CanNotHandleNonImageType extends BaseInvalidArgumentException
{
    /**
     * The unit.
     */
    protected string $extension;

    /**
     * Constructor.
     *
     * @param  int  $code
     */
    #[Pure]
    public function __construct(string $extension, $code = 0, ?Throwable $previous = null)
    {
        $this->extension = $extension;

        parent::__construct("Can not handle non image type file, Extension given: '$extension'", $code, $previous);
    }

    /**
     * Get the unit.
     */
    public function getExtension(): string
    {
        return $this->extension;
    }
}
