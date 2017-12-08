<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use InvalidArgumentException;

/**
 * A view is the visual representation of a model.
 */
final class View
{
    /** @var callable */
    private $callback;

    /** @var string */
    private $name;

    /** @var PresentationModel */
    private $presentationModel;

    /**
     * @param string            $name              The name of the view.
     * @param PresentationModel $presentationModel A presentation model holding the variables for this view.
     * @param callable          $callback          A callback to render this view.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, PresentationModel $presentationModel, callable $callback)
    {
        if ($name === '') {
            throw new InvalidArgumentException('The name of a view cannot be empty');
        }

        $this->name = $name;
        $this->presentationModel = $presentationModel;
        $this->callback = $callback;
    }

    /**
     * Renders the view.
     *
     * @return void
     */
    public function render()
    {
        call_user_func($this->callback, $this->presentationModel->getVariables());
    }

    /**
     * @return string The name of the view.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return PresentationModel
     */
    public function getPresentationModel(): PresentationModel
    {
        return $this->presentationModel;
    }

    /**
     * @param PresentationModel $presentationModel
     *
     * @return View
     */
    public function withPresentationModel(PresentationModel $presentationModel): self
    {
        $new = clone $this;
        $new->presentationModel = $presentationModel;

        return $new;
    }
}
