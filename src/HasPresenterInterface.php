<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * When implemented by a presentation model, the PresenterMiddleware will locate and invoke the presenter returned
 * by this interface.
 */
interface HasPresenterInterface
{
    /**
     * Returns the name by which to resolve the presenter through the DI container.
     *
     * @return string
     */
    public function getPresenterName(): string;
}
