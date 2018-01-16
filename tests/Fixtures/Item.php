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
     * @return string The class name of the presenter for this presentation model.
     */
    public function getPresenter(): string
    {
        return ItemPresenter::class;
    }
}
