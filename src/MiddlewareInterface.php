<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Psr\Http\Message\ServerRequestInterface;

interface MiddlewareInterface
{
    /**
     * @param View                   $view    The view to be processed by this middleware.
     * @param ServerRequestInterface $request The current HTTP request being handled.
     * @param callable               $next    The next middleware to call.
     *
     * @return View The processed view.
     */
    public function process(View $view, ServerRequestInterface $request, callable $next): View;
}
