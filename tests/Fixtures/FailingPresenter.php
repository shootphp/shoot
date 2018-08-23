<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;

final class FailingPresenter implements PresenterInterface
{
    /**
     * @param ServerRequestInterface $request           The current HTTP request being handled.
     * @param PresentationModel      $presentationModel The presentation model for the view being rendered.
     *
     * @return PresentationModel The populated presentation model.
     */
    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel
    {
        throw new RuntimeException('An error occurred');
    }
}
