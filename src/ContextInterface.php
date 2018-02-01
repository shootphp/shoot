<?php
declare(strict_types=1);

namespace Shoot\Shoot;

interface ContextInterface
{
    /**
     * @param string $name    The name of the attribute.
     * @param mixed  $default A default value is the attribute does not exist.
     *
     * @return mixed The value of the attribute, or the default if it does not exist.
     */
    public function getAttribute(string $name, $default = null);
}
