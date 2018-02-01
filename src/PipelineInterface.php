<?php
declare(strict_types=1);

namespace Shoot\Shoot;

interface PipelineInterface
{
    /**
     * Applies the given context to the pipeline, executes the given callback, and clears the context.
     *
     * @param ContextInterface $context
     * @param callable         $callback
     *
     * @return mixed The result as returned by the callback (if any).
     */
    public function withContext(ContextInterface $context, callable $callback);
}
