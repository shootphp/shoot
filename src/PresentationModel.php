<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * Holds the variables available to a view.
 */
class PresentationModel
{
    /**
     * @param mixed[] $variables
     */
    final public function __construct(array $variables = [])
    {
        $this->setVariables($variables);
    }

    /**
     * @return string The name of the presentation model.
     */
    final public function getName(): string
    {
        return static::class;
    }

    /**
     * @return mixed[]
     */
    final public function getVariables(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param mixed[] $variables
     *
     * @return PresentationModel
     */
    final public function withVariables(array $variables): self
    {
        $new = clone $this;
        $new->setVariables($variables);

        return $new;
    }

    /**
     * @param mixed[] $variables
     *
     * @return void
     */
    private function setVariables(array $variables)
    {
        $isGeneric = static::class === self::class;

        foreach ($variables as $variable => $value) {
            if (is_string($variable) && ($isGeneric || property_exists($this, $variable))) {
                $this->$variable = $value;
            }
        }
    }
}
