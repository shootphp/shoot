<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Shoot\Shoot\Pipeline;
use Shoot\Shoot\View;
use Twig_Compiler as Compiler;
use Twig_Node as Node;

/**
 * This node is added to the bottom of the display method of a Twig template and is used by Shoot to wrap its contents
 * in a callback.
 */
final class DisplayEndNode extends Node
{
    /**
     * @param string $templateName
     */
    public function __construct(string $templateName)
    {
        parent::__construct();

        $this->setTemplateName($templateName);
    }

    /**
     * @param Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler)
    {
        $pipeline = Pipeline::class;
        $templateName = $this->getTemplateName();
        $view = View::class;

        $compiler
            ->outdent()
            ->write("};\n\n")
            ->write("\$this->env\n")
            ->indent()
            ->write("->getExtension({$pipeline}::class)\n")
            ->write("->process(new {$view}('{$templateName}', \$presentationModel, \$callback));\n")
            ->outdent();
    }
}
