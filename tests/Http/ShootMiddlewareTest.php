<?php
declare(strict_types=1);

namespace Shoot\Http\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shoot\Shoot\Http\ShootMiddleware;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

final class ShootMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testHttpRequestShouldBeSetOnPipeline()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $view = ViewFactory::create();

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo($view), $this->equalTo($request))
            ->willReturn($view);

        $pipeline = new Pipeline([$middleware]);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($request))
            ->will($this->returnCallback(function () use ($pipeline, $view, $response) {
                $pipeline->process($view);

                return $response;
            }));

        $httpMiddleware = new ShootMiddleware($pipeline);
        $httpMiddleware->process($request, $handler);
    }
}
