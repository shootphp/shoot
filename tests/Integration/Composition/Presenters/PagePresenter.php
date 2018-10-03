<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Composition\Presenters;

use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;
use Shoot\Shoot\Tests\Integration\Composition\Models\Item;

final class PagePresenter implements PresenterInterface
{
    /**
     * Receives the current HTTP request context and the presentation model assigned to the view. If necessary,
     * populates the presentation model with data and returns it.
     *
     * @param ServerRequestInterface $request
     * @param PresentationModel      $presentationModel
     *
     * @return PresentationModel
     */
    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel
    {
        return $presentationModel->withVariables([
            'item' => new Item(),
        ]);
    }
}
