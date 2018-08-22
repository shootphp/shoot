<?php
declare(strict_types=1);

namespace Shoot\Shoot;

use Psr\Http\Message\ServerRequestInterface;

interface PipelineInterface
{
    /**
     * During the execution of the callback, any middleware in the pipeline will have access to the given request
     * object.
     *
     * @param ServerRequestInterface $request  The current HTTP request being handled.
     * @param callable               $callback A callback which should call Twig to render the root template.
     *
     * @return mixed The result as returned by the callback (if any).
     */
    public function withRequest(ServerRequestInterface $request, callable $callback);
}
