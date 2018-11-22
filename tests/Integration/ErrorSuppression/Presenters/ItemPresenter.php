<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\ErrorSuppression\Presenters;

use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;

final class ItemPresenter implements PresenterInterface
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
        if ($request->getAttribute('throw_logic_exception', 'n') === 'y') {
            return $presentationModel->withVariables([
                'throw_logic_exception' => true,
            ]);
        } elseif ($request->getAttribute('throw_runtime_exception', 'n') === 'y') {
            throw new RuntimeException('item_exception');
        }

        return $presentationModel;
    }
}
