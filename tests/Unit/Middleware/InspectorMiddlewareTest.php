<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Middleware\InspectorMiddleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;

final class InspectorMiddlewareTest extends TestCase
{
    public function testShouldLogDebugInformationToConsole(): void
    {
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $view = ViewFactory::create();
        $next = function (View $view): View {
            return $view;
        };

        $this->expectOutputRegex('/<script>.+<\/script>/');

        $middleware = new InspectorMiddleware();
        $middleware->process($view, $request, $next);
    }
}
