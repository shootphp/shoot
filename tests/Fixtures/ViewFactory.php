<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\View;

final class ViewFactory
{
    /**
     * @return View
     */
    public static function create(): View
    {
        return self::createWithCallback(function () {
            // noop
        });
    }

    /**
     * @param callable $callback
     *
     * @return View
     */
    public static function createWithCallback(callable $callback): View
    {
        $presentationModel = new class extends PresentationModel implements HasPresenterInterface
        {
            protected $variable = '';

            public function getPresenterName(): string
            {
                return 'MockPresenter';
            }
        };

        return new View('template.twig', $presentationModel, $callback);
    }
}
