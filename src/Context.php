<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * Provides the context in which middleware processes a view.
 */
final class Context implements ContextInterface
{
    /** @var mixed[] */
    private $attributes;

    /**
     * @param mixed[] $attributes The attributes to assign to the context.
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string $name    The name of the attribute.
     * @param mixed  $default A default value is the attribute does not exist.
     *
     * @return mixed The value of the attribute, or the default if it does not exist.
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }
}
