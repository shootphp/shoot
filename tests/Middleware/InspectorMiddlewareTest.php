<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Middleware\InspectorMiddleware;
use Shoot\Shoot\Tests\Fixtures\Item;
use Shoot\Shoot\Tests\Fixtures\MiddlewareCallback;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

final class InspectorMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testProcessShouldLogDebugInformationToConsole()
    {
        $this->expectOutputRegex('/<script>.+<\/script>/');

        /** @var ServerRequestInterface $request */
        $request = $this->prophesize(ServerRequestInterface::class)->reveal();

        $middleware = new InspectorMiddleware();
        $next = new MiddlewareCallback();
        $presentationModel = new Item();
        $view = ViewFactory::create($presentationModel);

        $middleware->process($view, $request, $next);
    }
}
