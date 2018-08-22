<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LogLevel;
use Shoot\Shoot\Middleware\LoggingMiddleware;
use Shoot\Shoot\Tests\Fixtures\Logger;
use Shoot\Shoot\Tests\Fixtures\MiddlewareCallback;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

final class LoggingMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testProcessShouldLogBasicDebugInformation()
    {
        $wasCalled = false;

        $logger = new Logger(function ($level, $message, $context) use (&$wasCalled) {
            $this->assertSame(LogLevel::DEBUG, $level);
            $this->assertSame('item.twig', $message);
            $this->assertArrayHasKey('presentation_model', $context);
            $this->assertArrayHasKey('time_taken', $context);
            $this->assertArrayHasKey('variables', $context);

            $wasCalled = true;
        });

        /** @var ServerRequestInterface $request */
        $request = $this->prophesize(ServerRequestInterface::class)->reveal();

        $middleware = new LoggingMiddleware($logger);
        $next = new MiddlewareCallback();
        $view = ViewFactory::create();

        $middleware->process($view, $request, $next);

        $this->assertTrue($wasCalled);
    }
}
