<?php
declare(strict_types=1);

namespace Shoot\Shoot\Middleware;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\PresenterInterface;
use Shoot\Shoot\View;

/**
 * Resolves presenters as defined by presentation models using a PSR-11 compliant DI container.
 */
final class PresenterMiddleware implements MiddlewareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @param ContainerInterface $container A PSR-11 compliant DI container holding the presenters.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param View                   $view    The view to be processed by this middleware.
     * @param ServerRequestInterface $request The current HTTP request being handled.
     * @param callable               $next    The next middleware to call.
     *
     * @return View The processed view.
     */
    public function process(View $view, ServerRequestInterface $request, callable $next): View
    {
        $presentationModel = $view->getPresentationModel();

        if ($presentationModel instanceof HasPresenterInterface) {
            $presenter = $this->loadPresenter($presentationModel);
            $presentationModel = $presenter->present($request, $presentationModel);
            $view = $view->withPresentationModel($presentationModel);
        }

        return $next($view);
    }

    /**
     * Load the presenter from the container.
     *
     * @param HasPresenterInterface $hasPresenter A presentation model which has a presenter defined.
     *
     * @throws ContainerExceptionInterface
     *
     * @return PresenterInterface The presenter as returned by the container.
     */
    private function loadPresenter(HasPresenterInterface $hasPresenter): PresenterInterface
    {
        return $this->container->get($hasPresenter->getPresenterName());
    }
}
