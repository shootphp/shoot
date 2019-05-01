<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\NodeVisitor;

use Shoot\Shoot\ModelAlreadyAssignedException;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Twig\Node\DisplayEndNode;
use Shoot\Shoot\Twig\Node\DisplayStartNode;
use Shoot\Shoot\Twig\Node\FindPresentationModelInterface;
use Shoot\Shoot\Twig\Node\ModelNode;
use SplObjectStorage;
use Twig\Environment;
use Twig\Node\ModuleNode;
use Twig\Node\Node;
use Twig\NodeVisitor\NodeVisitorInterface;
use Twig\Source;

/**
 * Walks through model tags to assign presentation models to templates.
 *
 * @internal
 */
final class ModelNodeVisitor implements FindPresentationModelInterface, NodeVisitorInterface
{
    /** @var SplObjectStorage */
    private $presentationModels;

    public function __construct()
    {
        $this->presentationModels = new SplObjectStorage();
    }

    /**
     * @param Node        $node
     * @param Environment $environment
     *
     * @return Node
     */
    public function enterNode(Node $node, Environment $environment): Node
    {
        if ($node instanceof ModelNode) {
            $this->assign($node->getSourceContext(), $node->getAttribute('presentation_model'));
        }

        return $node;
    }

    /**
     * @param Node        $node
     * @param Environment $environment
     *
     * @return Node
     */
    public function leaveNode(Node $node, Environment $environment): Node
    {
        if (!($node instanceof ModuleNode)) {
            return $node;
        }

        if ($node->hasAttribute('embedded_templates')) {
            /** @var ModuleNode $embeddedTemplate */
            foreach ($node->getAttribute('embedded_templates') as $embeddedTemplate) {
                $embeddedTemplate->setAttribute('is_embedded', true);
            }
        }

        $node->setNode('display_start', new DisplayStartNode($node, $this));
        $node->setNode('display_end', new DisplayEndNode($node));

        return $node;
    }

    /**
     * Returns the presentation model for the given view.
     *
     * @param Source $source
     *
     * @return string
     */
    public function for(Source $source): string
    {
        if (isset($this->presentationModels[$source])) {
            return (string)$this->presentationModels[$source];
        }

        return PresentationModel::class;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        // Should be between -10 and 10, with 0 as the default
        return -10;
    }

    /**
     * @param Source $source
     * @param string $presentationModel
     *
     * @return void
     *
     * @throws ModelAlreadyAssignedException
     */
    private function assign(Source $source, string $presentationModel): void
    {
        if (isset($this->presentationModels[$source])) {
            throw new ModelAlreadyAssignedException("A presentation model has already been assigned to {$source->getName()}");
        }

        $this->presentationModels[$source] = $presentationModel;
    }
}
