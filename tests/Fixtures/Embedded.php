<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;

final class Embedded extends PresentationModel implements HasPresenterInterface
{
    /** @var string */
    protected $subtitle = '';

    /** @var string */
    protected $title = '';

    /**
     * Returns the name by which to resolve the presenter through the DI container.
     *
     * @return string
     */
    public function getPresenterName(): string
    {
        return EmbeddedPresenter::class;
    }
}
