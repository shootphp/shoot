<?php
declare(strict_types=1);

namespace Shoot\Shoot\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\View;
use Throwable;

/**
 * Logs all views being processed by Shoot. It's recommended to add this before any other middleware.
 */
final class LoggingMiddleware implements MiddlewareInterface
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * Constructs a new instance of LoggingMiddleware. Requires a PSR-3 compliant logger.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
     *
     * @throws Throwable
     */
    public function process(View $view, ServerRequestInterface $request, callable $next): View
    {
        $context = [];
        $message = $view->getName();

        try {
            $startTime = microtime(true);

            /** @var View $view */
            $view = $next($view);

            $endTime = microtime(true);

            $presentationModel = $view->getPresentationModel();

            $context['presentation_model'] = $presentationModel->getName();

            if ($presentationModel instanceof HasPresenterInterface) {
                $context['presenter_name'] = $presentationModel->getPresenterName();
            }

            $context['time_taken'] = sprintf("%f seconds", $endTime - $startTime);

            if ($view->hasSuppressedException()) {
                $context['exception'] = $view->getSuppressedException();

                $this->logger->warning($message, $context);
            } else {
                $this->logger->debug($message, $context);
            }

            return $view;
        } catch (Throwable $exception) {
            $context['exception'] = $exception;

            $this->logger->error($message, $context);

            throw $exception;
        }
    }
}
