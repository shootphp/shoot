<?php
declare(strict_types=1);

namespace Shoot\Shoot;

interface MiddlewareInterface
{
    /**
     * @param View             $view    The view to be processed by this middleware.
     * @param ContextInterface $context The context in which to process the view.
     * @param callable         $next    The next middleware to call
     *
     * @return View The processed view.
     */
    public function process(View $view, ContextInterface $context, callable $next): View;
}
