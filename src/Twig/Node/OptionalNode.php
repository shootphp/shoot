<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Shoot\Shoot\SuppressedException;
use Twig_Compiler as Compiler;
use Twig_Error_Runtime as RuntimeError;
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
        $runtimeErrorClass = RuntimeError::class;
        $suppressedExceptionClass = SuppressedException::class;

        $compiler
            ->write("try {\n")
            ->indent()
            ->write("ob_start();\n\n")
            ->subcompile($this->getNode('body'))
            ->raw("\n")
            ->write("echo ob_get_clean();\n")
            ->outdent()
            ->write("} catch ({$runtimeErrorClass} \$exception) {\n")
            ->indent()
            ->write("ob_end_clean();\n\n")
            ->write("if (\$exception->getPrevious() === null) {\n")
            ->indent()
            ->write("throw \$exception;\n")
            ->outdent()
            ->write("}\n\n")
            ->write("\$suppressedException = new {$suppressedExceptionClass}(\$exception->getPrevious());\n")
            ->outdent()
            ->write("}\n\n");
    }
}
