<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use RuntimeException;
use Throwable;

/**
 * Represents an exception that was suppressed by the optional tag. The actual exception is available through
 * getPrevious().
 */
final class SuppressedException extends RuntimeException
{
    /**
     * Constructs an instance of SuppressedException.
     *
     * @param Throwable $previous
     */
    public function __construct(Throwable $previous)
    {
        parent::__construct('This exception was suppressed. See the previous exception for details', 0, $previous);
    }
}
