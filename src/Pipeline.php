<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Psr\Http\Message\ServerRequestInterface;

final class Pipeline
{
    /** @var callable */
    private $middleware;

    /** @var ServerRequestInterface */
    private $request;

    /**
     * @param MiddlewareInterface[] $middleware
     */
    public function __construct(array $middleware = [])
    {
        $this->middleware = $this->chainMiddleware($middleware);
    }

    /**
     * During the execution of the callback, any middleware in the pipeline will have access to the given request
     * object.
     *
     * @param ServerRequestInterface $request  The current HTTP request being handled.
     * @param callable               $callback A callback which should call Twig to render the root template.
     *
     * @return mixed The result as returned by the callback (if any).
     */
    public function withRequest(ServerRequestInterface $request, callable $callback)
    {
        try {
            $this->request = $request;

            return $callback();
        } finally {
            $this->request = null;
        }
    }

    /**
     * @param View $view
     *
     * @return void
     */
    public function process(View $view)
    {
        if ($this->request === null) {
            throw new MissingRequestException('Cannot process a view without a request set. This method should be called from the callback passed to Pipeline::withRequest');
        }

        call_user_func($this->middleware, $view);
    }

    /**
     * Chains the middleware into a single callable.
     *
     * @param MiddlewareInterface[] $middleware
     *
     * @return callable
     */
    private function chainMiddleware(array $middleware): callable
    {
        $middleware = array_reverse($middleware);

        return array_reduce($middleware, function (callable $next, MiddlewareInterface $middleware) {
            return function (View $view) use ($middleware, $next): View {
                return $middleware->process($view, $this->request, $next);
            };
        }, function (View $view): View {
            try {
                $view->render();

                return $view;
            } catch (SuppressedException $exception) {
                return $view->withSuppressedException($exception->getPrevious());
            }
        });
    }
}
