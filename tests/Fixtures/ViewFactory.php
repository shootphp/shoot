<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

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
        return new View('item.twig', new Item(), $callback);
    }
}
