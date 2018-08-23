<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Twig_Compiler as Compiler;
use Twig_Node as Node;

final class OptionalNode extends Node
{
    /**
     * @param Node   $body       The body of the suppressed tag.
     * @param int    $lineNumber The line number at which the tag occurs in the template.
     * @param string $tag        The name of the tag.
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
