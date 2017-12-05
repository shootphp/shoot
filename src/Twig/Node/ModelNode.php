<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Twig_Node as Node;

/**
 * Represents the model tag used to assign a presentation model to a view.
 */
final class ModelNode extends Node
{
    /**
     * @param string $presentationModel The given presentation model.
     * @param int    $lineNumber        The line number at which the tag occurs in the template.
     * @param string $tag               The name of the tag.
     */
    public function __construct(string $presentationModel, int $lineNumber, string $tag)
    {
        parent::__construct([], ['presentation_model' => $presentationModel], $lineNumber, $tag);
    }
}
