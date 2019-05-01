<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use InvalidArgumentException;
use Throwable;

/**
 * A view represents a template with its presentation model. You'll never have to construct a view manually, as this is
 * taken care of by Shoot's Twig extension. You'll have access to them in any middleware you implement.
 */
final class View
{
    /** @var callable */
    private $callback;

    /** @var string */
    private $name;

    /** @var PresentationModel */
    private $presentationModel;

    /** @var Throwable|null */
    private $suppressedException;

    /**
     * @param string            $name
     * @param PresentationModel $presentationModel
     * @param callable          $callback
     *
     * @throws InvalidArgumentException
     *
     * @internal
     */
    public function __construct(string $name, PresentationModel $presentationModel, callable $callback)
    {
        $this->name = $name !== '' ? $name : 'unknown template';
        $this->presentationModel = $presentationModel;
        $this->callback = $callback;
    }

    /**
     * @return void
     *
     * @internal
     */
    public function render(): void
    {
        call_user_func($this->callback, $this->presentationModel->getVariables());
    }

    /**
     * Returns the name of the view.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the presentation model.
     *
     * @return PresentationModel
     */
    public function getPresentationModel(): PresentationModel
    {
        return $this->presentationModel;
    }

    /**
     * Returns a clone of this view with the new presentation model set.
     *
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

    /**
     * Returns the exception that was suppressed while rendering this view.
     *
     * @return Throwable|null
     */
    public function getSuppressedException(): ?Throwable
    {
        return $this->suppressedException;
    }

    /**
     * Returns whether an exception was suppressed while rendering this view.
     *
     * @return bool
     */
    public function hasSuppressedException(): bool
    {
        return $this->suppressedException !== null;
    }

    /**
     * Returns a clone of this view with the suppressed exception set.
     *
     * @param Throwable|null $exception
     *
     * @return View
     */
    public function withSuppressedException(?Throwable $exception): self
    {
        $new = clone $this;
        $new->suppressedException = $exception;

        return $new;
    }
}
