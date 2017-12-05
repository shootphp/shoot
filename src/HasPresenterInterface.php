<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * Should be implemented by presentation models with a presenter.
 */
interface HasPresenterInterface
{
    /**
     * @return string The class name of the presenter for this presentation model.
     */
    public function getPresenter(): string;
}
