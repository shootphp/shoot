<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;

final class Item extends PresentationModel implements HasPresenterInterface
{
    /** @var string */
    protected $description = '';

    /** @var string */
    protected $name = '';

    /**
     * @return string The name by which to resolve the presenter through the DI container.
     */
    public function getPresenterName(): string
    {
        return ItemPresenter::class;
    }
}
