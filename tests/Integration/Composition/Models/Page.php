<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Composition\Models;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Integration\Composition\Presenters\PagePresenter;

final class Page extends PresentationModel implements HasPresenterInterface
{
    /** @var Item */
    protected $item = null;

    /**
     * Returns the name by which to resolve the presenter through the DI container.
     *
     * @return string
     */
    public function getPresenterName(): string
    {
        return PagePresenter::class;
    }
}
