<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\View;

final class MiddlewareCallback
{
    /**
     * @param View $view
     *
     * @return View
     */
    public function __invoke(View $view): View
    {
        return $view;
    }
}
