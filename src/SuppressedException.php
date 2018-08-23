<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use RuntimeException;
use Throwable;

final class SuppressedException extends RuntimeException
{
    /**
     * @param Throwable $previous
     */
    public function __construct(Throwable $previous)
    {
        parent::__construct('This exception was suppressed. See the previous exception for details', 0, $previous);
    }
}
