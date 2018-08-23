<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LogLevel;
use RuntimeException;
use Shoot\Shoot\Middleware\LoggingMiddleware;
use Shoot\Shoot\Tests\Fixtures\Logger;
use Shoot\Shoot\Tests\Fixtures\MiddlewareCallback;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

final class LoggingMiddlewareTest extends TestCase
{
    /** @var callable */
    private $next;

    /** @var ServerRequestInterface */
    private $request;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->next = new MiddlewareCallback();
        $this->request = $this->prophesize(ServerRequestInterface::class)->reveal();
    }

    /**
     * @return void
     */
    public function testProcessShouldLogBasicDebugInformation()
    {
        $view = ViewFactory::create();
        $wasCalled = false;

        $middleware = new LoggingMiddleware(new Logger(function ($level, $message, $context) use (&$wasCalled) {
            $this->assertSame(LogLevel::DEBUG, $level);
            $this->assertSame('item.twig', $message);
            $this->assertArrayHasKey('presentation_model', $context);
            $this->assertArrayHasKey('presenter_name', $context);
            $this->assertArrayHasKey('time_taken', $context);
            $this->assertArrayHasKey('variables', $context);

            $wasCalled = true;
        }));

        $middleware->process($view, $this->request, $this->next);

        $this->assertTrue($wasCalled);
    }

    /**
     * @return void
     */
    public function testProcessShouldLogSuppressedExceptions()
    {
        $view = ViewFactory::create()->withSuppressedException(new RuntimeException());
        $wasCalled = false;

        $middleware = new LoggingMiddleware(new Logger(function ($level, $message, $context) use (&$wasCalled) {
            $this->assertSame(LogLevel::WARNING, $level);
            $this->assertArrayHasKey('exception', $context);

            $wasCalled = true;
        }));

        $middleware->process($view, $this->request, $this->next);

        $this->assertTrue($wasCalled);
    }
}
