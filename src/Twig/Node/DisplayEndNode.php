<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Shoot\Shoot\Extension;
use Shoot\Shoot\View;
use Twig_Compiler as Compiler;
use Twig_Node as Node;
use Twig_Node_Module as ModuleNode;

/**
 * This node is added to the bottom of the display method of a Twig template and is used by Shoot to wrap its contents
 * in a callback.
 *
 * @internal
 */
final class DisplayEndNode extends Node
{
    /** @var ModuleNode */
    private $module;

    /**
     * @param ModuleNode $module
     */
    public function __construct(ModuleNode $module)
    {
        parent::__construct();

        $this->module = $module;

        $this->setTemplateName($module->getTemplateName());
    }

    /**
     * @param Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler)
    {
        $extension = Extension::class;
        $templateName = $this->getTemplateName();
        $view = View::class;

        $compiler
            ->raw("\n")
            ->write("if (\$suppressedException instanceof Shoot\\Shoot\\SuppressedException) {\n")
            ->indent()
            ->write("throw \$suppressedException;\n")
            ->outdent()
            ->write("}\n")
            ->outdent()
            ->write("};\n\n")
            ->write("\$this->env\n")
            ->indent()
            ->write("->getExtension({$extension}::class)\n")
            ->write("->process(new {$view}('{$templateName}', \$presentationModel, \$callback));\n")
            ->outdent();
    }
}
