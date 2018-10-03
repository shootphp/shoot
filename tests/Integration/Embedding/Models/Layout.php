<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Embedding\Models;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Integration\Embedding\Presenters\LayoutPresenter;

final class Layout extends PresentationModel implements HasPresenterInterface
{
    /** @var string[] */
    protected $links = [];

    /** @var string */
    protected $main_class = 'main--default';

    /** @var string */
    protected $navigation_class = 'navigation--default';

    /**
     * Returns the name by which to resolve the presenter through the DI container.
     *
     * @return string
     */
    public function getPresenterName(): string
    {
        return LayoutPresenter::class;
    }
}
