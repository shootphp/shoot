<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig\Node;

use Twig\Source;

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
     * @param Source $source
     *
     * @return string
     */
    public function for(Source $source): string;
}
