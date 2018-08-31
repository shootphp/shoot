<?php
declare(strict_types=1);

namespace Shoot\Shoot\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\SuppressedException;
use Shoot\Shoot\View;

/**
 * Suppresses exceptions thrown from within the optional tag. It's recommended to add this as the last middleware.
 */
final class SuppressionMiddleware implements MiddlewareInterface
{
    /**
     * Process the view within the context of the current HTTP request, either before or after calling the next
     * middleware. Returns the processed view.
     *
     * @param View                   $view
     * @param ServerRequestInterface $request
     * @param callable               $next
     *
     * @return View
     */
    public function process(View $view, ServerRequestInterface $request, callable $next): View
    {
        try {
            return $next($view);
        } catch (SuppressedException $exception) {
            return $view->withSuppressedException($exception->getPrevious());
        }
    }
}
