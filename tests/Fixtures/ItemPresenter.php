<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;
use Shoot\Shoot\Utilities\HasDataTrait;

final class ItemPresenter implements PresenterInterface
{
    use HasDataTrait;

    /**
     * @param ServerRequestInterface $request           The current HTTP request being handled.
     * @param PresentationModel      $presentationModel The presentation model for the view being rendered.
     *
     * @return PresentationModel The populated presentation model.
     */
    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel
    {
        if (!$this->hasData($presentationModel)) {
            $presentationModel = $presentationModel->withVariables([
                'name' => 'item',
                'description' => 'description',
            ]);
        }

        return $presentationModel;
    }
}
