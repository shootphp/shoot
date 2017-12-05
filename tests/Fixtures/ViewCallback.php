<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

final class ViewCallback
{
    /**
     * @param mixed[] $variables
     *
     * @return void
     */
    public function __invoke(array $variables)
    {
        // noop
    }
}
