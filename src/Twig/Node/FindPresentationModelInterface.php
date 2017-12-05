<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

/**
 * Finds the presentation model for a given view.
 */
interface FindPresentationModelInterface
{
    /**
     * Find the presentation model for the given view.
     *
     * @param string $view The name of the view.
     *
     * @return string The name of the presentation model.
     */
    public function for(string $view): string;
}
