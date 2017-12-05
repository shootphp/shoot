<?php
declare(strict_types=1);

namespace Shoot\Shoot\Middleware;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Shoot\Shoot\Context;
use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;
use Shoot\Shoot\View;

/**
 * Resolves presenters as defined by presentation models using a PSR-11 compliant DI container. If the model does not
 * contain data, the presenter is invoked to update the model.
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
     * @param View     $view    The view to be processed by this middleware.
     * @param Context  $context The context in which to process the view.
     * @param callable $next    The next middleware to call
     *
     * @throws ContainerExceptionInterface
     *
     * @return View The processed view.
     */
    public function process(View $view, Context $context, callable $next): View
    {
        $presentationModel = $view->getPresentationModel();

        if (!$this->hasData($presentationModel) && $presentationModel instanceof HasPresenterInterface) {
            $presenter = $this->loadPresenter($presentationModel);
            $presentationModel = $presenter->present($context, $presentationModel);
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
        return $this->container->get($hasPresenter->getPresenter());
    }

    /**
     * Determine whether the presentation model already holds data.
     *
     * @param PresentationModel $presentationModel The presentation model for which to determine whether it holds data.
     *
     * @return bool Whether the presentation model already holds data.
     */
    private function hasData(PresentationModel $presentationModel): bool
    {
        foreach ($presentationModel->getVariables() as $value) {
            if (!empty($value)) {
                return true;
            }
        }

        return false;
    }
}
