<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Twig_Compiler as Compiler;
use Twig_Node as Node;

/**
 * This node is added to the top of the display method of a Twig template and is used by Shoot to wrap its contents in
 * a callback.
 */
final class DisplayStartNode extends Node
{
    /** @var FindPresentationModelInterface */
    private $findPresentationModel;

    /**
     * @param string                         $templateName
     * @param FindPresentationModelInterface $findPresentationModel
     */
    public function __construct(string $templateName, FindPresentationModelInterface $findPresentationModel)
    {
        parent::__construct();

        $this->setTemplateName($templateName);
        $this->findPresentationModel = $findPresentationModel;
    }

    /**
     * @param Compiler $compiler
     *
     * @return void
     */
    public function compile(Compiler $compiler)
    {
        $presentationModel = $this->findPresentationModel->for($this->getTemplateName());

        $compiler
            ->write("\$presentationModel = new $presentationModel(\$context);\n\n")
            ->write("\$callback = function (array \$context) use (\$blocks) {\n")
            ->indent();
    }
}
