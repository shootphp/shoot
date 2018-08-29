<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * Represents the data available to a view.
 */
class PresentationModel
{
    /**
     * Constructs an instance of PresentationModel. Takes an associative array of variables to be set on the model. Only
     * sets variables which have been defined in the model.
     *
     * @param mixed[] $variables
     */
    final public function __construct(array $variables = [])
    {
        $this->setVariables($variables);
    }

    /**
     * Returns the name of the presentation model.
     *
     * @return string
     */
    final public function getName(): string
    {
        return static::class;
    }

    /**
     * Returns a single variable, or the given default value it it's missing.
     *
     * @param string $variable
     * @param mixed  $default
     *
     * @return mixed
     */
    final public function getVariable(string $variable, $default = null)
    {
        return $this->$variable ?? $default;
    }

    /**
     * Returns all variables defined in the model.
     *
     * @return mixed[]
     */
    final public function getVariables(): array
    {
        return get_object_vars($this);
    }

    /**
     * Returns a clone of this presentation model with the new variables set.
     *
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
        foreach ($variables as $variable => $value) {
            if ($this->variableExists($variable)) {
                $this->$variable = $value;
            }
        }
    }

    /**
     * @param string $variable
     *
     * @return bool
     */
    private function variableExists(string $variable): bool
    {
        if (static::class === self::class) {
            return true;
        }

        return property_exists($this, $variable);
    }
}
