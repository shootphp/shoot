<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\View;

final class ViewFactory
{
    /**
     * @param PresentationModel $presentationModel
     * @param callable          $callback
     * @param string            $name
     *
     * @return View
     */
    public static function create(
        PresentationModel $presentationModel = null,
        callable $callback = null,
        string $name = ''
    ): View {
        if ($presentationModel === null) {
            $presentationModel = new Item();
        }

        if ($callback === null) {
            $callback = new ViewCallback();
        }

        if ($name === '') {
            $name = 'item.twig';
        }

        return new View($name, $presentationModel, $callback);
    }
}
