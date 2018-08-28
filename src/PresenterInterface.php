<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Presenters are responsible for populating your presentation models with data.
 */
interface PresenterInterface
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
    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel;
}
