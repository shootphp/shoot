<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Models\Models;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Integration\Models\Presenters\PagePresenter;

final class Page extends PresentationModel implements HasPresenterInterface
{
    /** @var string */
    protected $content = '';

    /** @var string */
    protected $title = '';

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
