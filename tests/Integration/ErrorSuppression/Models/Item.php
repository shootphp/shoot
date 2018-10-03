<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\ErrorSuppression\Models;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Integration\ErrorSuppression\Presenters\ItemPresenter;

final class Item extends PresentationModel implements HasPresenterInterface
{
    /** @var bool */
    protected $throw_logic_exception = false;

    /**
     * Returns the name by which to resolve the presenter through the DI container.
     *
     * @return string
     */
    public function getPresenterName(): string
    {
        return ItemPresenter::class;
    }
}
