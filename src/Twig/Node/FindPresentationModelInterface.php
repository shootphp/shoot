<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

/**
 * Finds the presentation model for a given view.
 *
 * @internal
 */
interface FindPresentationModelInterface
{
    /**
     * Returns the presentation model for the given view.
     *
     * @param string $view
     *
     * @return string
     */
    public function for(string $view): string;
}
