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
 * Resolves presenters for presentation models implementing the HasPresenterInterface using a PSR-11 compliant
 * container.
 */
final class PresenterMiddleware implements MiddlewareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * Constructs a new instance of PresenterMiddleware. Requires a PSR-11 compliant container capable of resolving
     * your presenters.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

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
        $presentationModel = $view->getPresentationModel();

        if ($presentationModel instanceof HasPresenterInterface) {
            $presenter = $this->loadPresenter($presentationModel);
            $presentationModel = $presenter->present($request, $presentationModel);
            $view = $view->withPresentationModel($presentationModel);
        }

        return $next($view);
    }

    /**
     * @param HasPresenterInterface $hasPresenter
     *
     * @return PresenterInterface
     *
     * @throws ContainerExceptionInterface
     */
    private function loadPresenter(HasPresenterInterface $hasPresenter): PresenterInterface
    {
        return $this->container->get($hasPresenter->getPresenterName());
    }
}
