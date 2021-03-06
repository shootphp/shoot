<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\MissingRequestException;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use stdClass;

final class PipelineTest extends TestCase
{
    /** @var ServerRequestInterface|MockObject */
    private $request;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);

        parent::setUp();
    }

    public function testShouldCallMiddleware(): void
    {
        $view = ViewFactory::create();

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware
            ->expects($this->once())
            ->method('process')
            ->willReturn($view);

        $pipeline = new Pipeline([$middleware]);

        $pipeline->withRequest($this->request, function () use ($pipeline, $view) {
            $pipeline->process($view);
        });
    }

    public function testShouldRenderView(): void
    {
        $pipeline = new Pipeline();

        /** @var callable|MockObject $callback */
        $callback = $this
            ->getMockBuilder(stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callback
            ->expects($this->once())
            ->method('__invoke');

        $view = ViewFactory::createWithCallback($callback);

        $pipeline->withRequest($this->request, function () use ($pipeline, $view) {
            $pipeline->process($view);
        });
    }

    public function testShouldThrowIfNoRequestWasSet(): void
    {
        $pipeline = new Pipeline();
        $view = ViewFactory::create();

        $this->expectException(MissingRequestException::class);

        $pipeline->process($view);
    }
}
