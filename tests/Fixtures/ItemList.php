<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;

final class ItemList extends PresentationModel implements HasPresenterInterface
{
    /** @var Item[] */
    protected $items = [];

    /**
     * @return string The class name of the presenter for this presentation model.
     */
    public function getPresenterName(): string
    {
        return ItemListPresenter::class;
    }
}
