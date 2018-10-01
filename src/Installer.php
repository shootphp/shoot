<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Shoot\Shoot\Twig\PatchingCompiler;
use Twig_Environment as Environment;

/**
 * Installs Shoot in an instance of Twig.
 */
final class Installer
{
    /** @var Pipeline */
    private $pipeline;

    /**
     * @param Pipeline $pipeline
     */
    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Returns a Twig instance with Shoot installed. Does not modify the original instance.
     *
     * @param Environment $twig
     *
     * @return Environment
     */
    public function install(Environment $twig): Environment
    {
        $twig = clone $twig;

        $extension = new Extension($this->pipeline);
        $twig->addExtension($extension);

        $compiler = new PatchingCompiler($twig);
        $twig->setCompiler($compiler);

        return $twig;
    }
}
