<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Psr\Http\Message\ServerRequestInterface;

interface PresenterInterface
{
    /**
     * @param ServerRequestInterface $request           The current HTTP request being handled.
     * @param PresentationModel      $presentationModel The presentation model for the view being rendered.
     *
     * @return PresentationModel The populated presentation model.
     */
    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel;
}
