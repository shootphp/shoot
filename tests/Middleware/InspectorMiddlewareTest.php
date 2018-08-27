<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Middleware\InspectorMiddleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;

final class InspectorMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldLogDebugInformationToConsole()
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
