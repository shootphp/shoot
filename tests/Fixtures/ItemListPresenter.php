<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;

final class ItemListPresenter implements PresenterInterface
{
    /**
     * @param ServerRequestInterface $request           The current HTTP request being handled.
     * @param PresentationModel      $presentationModel The presentation model for the view being rendered.
     *
     * @return PresentationModel The populated presentation model.
     */
    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel
    {
        return $presentationModel->withVariables([
            'items' => [
                new Item([
                    'name' => 'item 1',
                    'description' => 'description',
                ]),
                new Item([
                    'name' => 'item 2',
                    'description' => 'description',
                ]),
                new Item([
                    'name' => 'item 3',
                    'description' => 'description',
                ]),
            ],
        ]);
    }
}
