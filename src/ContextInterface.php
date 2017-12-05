<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * Provides an interface to apply a context to the pipeline as it processes a view.
 */
interface ContextInterface
{
    /**
     * Apply the given context attributes to the pipeline.
     *
     * @param mixed[] $context
     *
     * @return void
     */
    public function applyContext(array $context);

    /**
     * Clear the current context.
     *
     * @return void
     */
    public function clearContext();

    /**
     * Applies the given context to the pipeline, executes the given callback, and clears the context. This method
     * exists merely for convenience.
     *
     * @param mixed[]  $context
     * @param callable $callback
     *
     * @return mixed The result as returned by the callback (if any).
     */
    public function withContext(array $context, callable $callback);
}
