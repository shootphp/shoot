<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use RuntimeException;

/**
 * This exception is thrown when an attempt is made to process a view without having set the current request context
 * first. Views should be processed from the callback passed to Pipeline::withRequest.
 */
final class MissingRequestException extends RuntimeException
{
}
