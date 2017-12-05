<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Shoot\Shoot\Context;
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
            $this->assertSame('product.twig', $message);
            $this->assertArrayHasKey('presentation_model', $context);
            $this->assertArrayHasKey('time_taken', $context);
            $this->assertArrayHasKey('variables', $context);

            $wasCalled = true;
        });

        $middleware = new LoggingMiddleware($logger);
        $context = new Context();
        $next = new MiddlewareCallback();
        $view = ViewFactory::create();

        $middleware->process($view, $context, $next);

        $this->assertTrue($wasCalled);
    }
}
