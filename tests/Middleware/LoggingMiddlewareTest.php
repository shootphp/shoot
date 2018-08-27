<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

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

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->next = function (View $view): View {
            return $view;
        };
    }

    /**
     * @return void
     */
    public function testShouldLogBasicDebugInformation()
    {
        $view = ViewFactory::create();

        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('debug')
            ->with(
                $this->equalTo('item.twig'),
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

    /**
     * @return void
     */
    public function testShouldLogSuppressedExceptions()
    {
        $view = ViewFactory::create()->withSuppressedException(new RuntimeException());

        /** @var LoggerInterface|MockObject $logger */
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('warning')
            ->with(
                $this->equalTo('item.twig'),
                $this->arrayHasKey('exception')
            );

        $middleware = new LoggingMiddleware($logger);
        $middleware->process($view, $this->request, $this->next);
    }
}
