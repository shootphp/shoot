<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * Should be implemented by presentation models with a presenter.
 */
interface HasPresenterInterface
{
    /**
     * @return string The name by which to resolve the presenter through the DI container.
     */
    public function getPresenterName(): string;
}
