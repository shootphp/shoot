<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Twig_Compiler as Compiler;
use Twig_Node as Node;
use Twig_Node_Module as ModuleNode;

/**
 * This node is added to the top of the display method of a Twig template and is used by Shoot to wrap the method's
 * contents in a callback.
 *
 * @internal
 */
final class DisplayStartNode extends Node
{
    /** @var FindPresentationModelInterface */
    private $findPresentationModel;

    /** @var ModuleNode */
    private $module;

    /**
     * @param ModuleNode                     $module
     * @param FindPresentationModelInterface $findPresentationModel
     */
    public function __construct(ModuleNode $module, FindPresentationModelInterface $findPresentationModel)
    {
        parent::__construct();

        $this->module = $module;
        $this->findPresentationModel = $findPresentationModel;

        $this->setTemplateName($module->getTemplateName());

    }

    /**
     * @param Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler)
    {
        if ($this->module->hasAttribute('is_embedded')) {
            return;
        }

        $presentationModel = $this->findPresentationModel->for($this->getTemplateName());

        $compiler
            ->write("\$presentationModel = new $presentationModel(\$context);\n\n")
            ->write("\$callback = function (array \$context) use (\$blocks) {\n")
            ->indent()
            ->write("\$suppressedException = null;\n\n");
    }
}
