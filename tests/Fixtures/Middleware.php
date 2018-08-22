<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\View;

final class Middleware implements MiddlewareInterface
{
    /** @var callable */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable = null)
    {
        $this->callable = $callable;
    }

    /**
     * @param View                   $view    The view to be processed by this middleware.
     * @param ServerRequestInterface $request The current HTTP request being handled.
     * @param callable               $next    The next middleware to call.
     *
     * @return View The processed view.
     */
    public function process(View $view, ServerRequestInterface $request, callable $next = null): View
    {
        if ($next === null) {
            $next = function (View $view): View {
                return $view;
            };
        }

        return call_user_func($this->callable, $view, $request, $next) ?? $view;
    }
}
