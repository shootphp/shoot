<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Shoot\Shoot\Twig\NodeVisitor\ModelNodeVisitor;
use Shoot\Shoot\Twig\TokenParser\ModelTokenParser;
use Shoot\Shoot\Twig\TokenParser\OptionalTokenParser;
use Twig\Extension\ExtensionInterface;
use Twig\NodeVisitor\NodeVisitorInterface;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * This extension for Twig will enable the use of Shoot.
 *
 * @internal
 */
final class Extension implements ExtensionInterface
{
    /** @var Pipeline */
    private $pipeline;

    /**
     * Constructs a new instance of Extension. Takes an instance of the Shoot pipeline.
     *
     * @param Pipeline $pipeline
     */
    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * @param View $view
     *
     * @return void
     *
     * @internal
     */
    public function process(View $view): void
    {
        $this->pipeline->process($view);
    }

    /**
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
        return [
            new ModelTokenParser(),
            new OptionalTokenParser(),
        ];
    }
}
