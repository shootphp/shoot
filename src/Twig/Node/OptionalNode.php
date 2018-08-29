<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Twig_Compiler as Compiler;
use Twig_Node as Node;

/**
 * Represents the optional tag used to suppress runtime exceptions in templates.
 *
 * @internal
 */
final class OptionalNode extends Node
{
    /**
     * @param Node   $body
     * @param int    $lineNumber
     * @param string $tag
     */
    public function __construct(Node $body, int $lineNumber, string $tag)
    {
        parent::__construct(['body' => $body], [], $lineNumber, $tag);
    }

    /**
     * @param Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler)
    {
        $compiler
            ->write("try {\n")
            ->indent()
            ->subcompile($this->getNode('body'))
            ->outdent()
            ->write("} catch (\\Twig_Error_Runtime \$exception) {\n")
            ->indent()
            ->write("if (\$exception->getPrevious() === null) {\n")
            ->indent()
            ->write("throw \$exception;\n")
            ->outdent()
            ->write("}\n\n")
            ->write("\$suppressedException = new \\Shoot\\Shoot\\SuppressedException(\$exception->getPrevious());\n")
            ->outdent()
            ->write("}\n\n");
    }
}
