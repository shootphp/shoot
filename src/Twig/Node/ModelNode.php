<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Twig_Node as Node;

/**
 * Represents the model tag used to assign a presentation model to a view.
 *
 * @internal
 */
final class ModelNode extends Node
{
    /**
     * @param string $presentationModel
     * @param int    $lineNumber
     * @param string $tag
     */
    public function __construct(string $presentationModel, int $lineNumber, string $tag)
    {
        parent::__construct([], ['presentation_model' => $presentationModel], $lineNumber, $tag);
    }
}
