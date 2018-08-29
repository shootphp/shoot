<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Psr\Http\Message\ServerRequestInterface;

/**
 * You can extend Shoot's functionality through middleware by implementing this interface. The middleware should be
 * passed into the pipeline.
 */
interface MiddlewareInterface
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
    public function process(View $view, ServerRequestInterface $request, callable $next): View;
}
