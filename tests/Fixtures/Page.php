<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;

final class Page extends PresentationModel implements HasPresenterInterface
{
    /** @var string */
    protected $body = '';

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
