<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Shoot\Shoot\Twig\NodeVisitor\ModelNodeVisitor;
use Shoot\Shoot\Twig\TokenParser\ModelTokenParser;
use Twig_ExtensionInterface as ExtensionInterface;
use Twig_Filter as TwigFilter;
use Twig_Function as TwigFunction;
use Twig_NodeVisitorInterface as NodeVisitorInterface;
use Twig_Test as TwigTest;
use Twig_TokenParserInterface as TokenParserInterface;

final class Pipeline implements ExtensionInterface, PipelineInterface
{
    /** @var ContextInterface */
    private $context;

    /** @var callable */
    private $middleware;

    /**
     * @param MiddlewareInterface[] $middleware
     */
    public function __construct(array $middleware = [])
    {
        $this->middleware = $this->chainMiddleware($middleware);

        $this->clearContext();
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
                return $middleware->process($view, $this->context, $next);
            };
        }, function (View $view): View {
            $view->render();

            return $view;
        });
    }

    /**
     * Applies the given context to the pipeline, executes the given callback, and clears the context.
     *
     * @param ContextInterface $context
     * @param callable         $callback
     *
     * @return mixed The result as returned by the callback (if any).
     */
    public function withContext(ContextInterface $context, callable $callback)
    {
        try {
            $this->applyContext($context);

            return $callback();
        } finally {
            $this->clearContext();
        }
    }

    /**
     * Apply the given context attributes to the pipeline.
     *
     * @param ContextInterface $context
     *
     * @return void
     */
    private function applyContext(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * Clear the current context.
     *
     * @return void
     */
    private function clearContext()
    {
        $this->applyContext(new Context());
    }

    /**
     * @internal This method is used by the compiled Twig templates to access the pipeline. It should not be used
     * directly.
     *
     * @param View $view
     *
     * @return void
     */
    public function process(View $view)
    {
        call_user_func($this->middleware, $view);
    }

    /**
     *§
     * Returns a list of filters to add to the existing list.
     *
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('variables', function (PresentationModel $presentationModel): array {
                return $presentationModel->getVariables();
            }),
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [];
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return NodeVisitorInterface[]
     */
    public function getNodeVisitors(): array
    {
        return [new ModelNodeVisitor()];
    }

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array[] First array of unary operators, second array of binary operators
     */
    public function getOperators(): array
    {
        return [];
    }

    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return TwigTest[]
     */
    public function getTests(): array
    {
        return [
            new TwigTest('model', function ($value): bool {
                return $value instanceof PresentationModel;
            }),
        ];
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return TokenParserInterface[]
     */
    public function getTokenParsers(): array
    {
        return [new ModelTokenParser()];
    }
}
