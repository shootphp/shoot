<?php
declare(strict_types=1);

namespace Shoot\Shoot\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shoot\Shoot\Pipeline;

/**
 * This HTTP middleware makes sure the request object is always set before you render your templates. Make sure to place
 * it after any other middleware that might enrich the request object, but before your first call to Twig.
 */
final class ShootMiddleware implements MiddlewareInterface
{
    /** @var Pipeline */
    private $pipeline;

    /**
     * Constructs a new instance of ShootMiddleware. The same instance of the Pipeline as passed into the Twig extension
     * must be used here.
     *
     * @param Pipeline $pipeline
     */
    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->pipeline->withRequest($request, function () use ($request, $handler): ResponseInterface {
            return $handler->handle($request);
        });
    }
}
