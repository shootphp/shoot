<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;

final class ItemListPresenter implements PresenterInterface
{
    /**
     * @param mixed             $context
     * @param PresentationModel $presentationModel
     *
     * @return PresentationModel
     */
    public function present($context, PresentationModel $presentationModel): PresentationModel
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
