<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\NodeVisitor;

use LogicException;
use Shoot\Shoot\ModelAlreadyAssignedException;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Twig\Node\DisplayEndNode;
use Shoot\Shoot\Twig\Node\DisplayStartNode;
use Shoot\Shoot\Twig\Node\FindPresentationModelInterface;
use Shoot\Shoot\Twig\Node\ModelNode;
use Twig_Environment as Environment;
use Twig_Node as Node;
use Twig_Node_Module as ModuleNode;
use Twig_NodeVisitorInterface as NodeVisitorInterface;

final class ModelNodeVisitor implements FindPresentationModelInterface, NodeVisitorInterface
{
    /** @var string[] */
    private $presentationModels = [];

    /**
     * Called before child nodes are visited.
     *
     * @param Node        $node
     * @param Environment $environment
     *
     * @return Node The modified node.
     */
    public function enterNode(Node $node, Environment $environment): Node
    {
        if ($node instanceof ModelNode) {
            $this->assign($node->getTemplateName(), $node->getAttribute('presentation_model'));
        }

        return $node;
    }

    /**
     * Called after child nodes are visited.
     *
     * @param Node        $node
     * @param Environment $environment
     *
     * @return Node The modified node.
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
     * Find the presentation model for the given view.
     *
     * @param string $view The name of the view.
     *
     * @return string The name of the presentation model.
     */
    public function for(string $view): string
    {
        return $this->presentationModels[$view] ?? PresentationModel::class;
    }

    /**
     * @return int The priority for this visitor.
     */
    public function getPriority(): int
    {
        // Should be between -10 and 10, with 0 as the default
        return -10;
    }

    /**
     * Assign a presentation model to the given view.
     *
     * @param string $view              The name of the view.
     * @param string $presentationModel The name of the presentation model.
     *
     * @throws LogicException
     *
     * @return void
     */
    private function assign(string $view, string $presentationModel)
    {
        if (isset($this->presentationModels[$view])) {
            throw new ModelAlreadyAssignedException("A presentation model has already been assigned to {$view}");
        }

        $this->presentationModels[$view] = $presentationModel;
    }
}
