<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit\Middleware;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Shoot\Shoot\Middleware\LoggingMiddleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;

final class LoggingMiddlewareTest extends TestCase
{
    /** @var callable */
    private $next;

    /** @var ServerRequestInterface|MockObject */
    private $request;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->next = function (View $view): View {
            return $view;
        };

        parent::setUp();
    }

    public function testShouldLogBasicDebugInformation(): void
    {
        $view = ViewFactory::create();

        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('debug')
            ->with(
                $this->equalTo('template.twig'),
                $this->callback(function (array $context): bool {
                    $diff = array_diff(
                        array_keys($context),
                        ['presentation_model', 'presenter_name', 'time_taken', 'variables']
                    );

                    return count($diff) === 0;
                })
            );

        $middleware = new LoggingMiddleware($logger);
        $middleware->process($view, $this->request, $this->next);
    }

    public function testShouldLogSuppressedExceptions(): void
    {
        $view = ViewFactory::create()->withSuppressedException(new RuntimeException());

        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('warning')
            ->with(
                $this->equalTo('template.twig'),
                $this->arrayHasKey('exception')
            );

        $middleware = new LoggingMiddleware($logger);
        $middleware->process($view, $this->request, $this->next);
    }

    public function testShouldLogUncaughtExceptions(): void
    {
        $view = ViewFactory::create();

        $next = function () {
            throw new Exception();
        };

        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('template.twig'),
                $this->arrayHasKey('exception')
            );

        $this->expectException(Exception::class);

        $middleware = new LoggingMiddleware($logger);
        $middleware->process($view, $this->request, $next);
    }
}
