<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Context;
use Shoot\Shoot\Middleware\InspectorMiddleware;
use Shoot\Shoot\Tests\Fixtures\MiddlewareCallback;
use Shoot\Shoot\Tests\Fixtures\ProductPresentationModel;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

final class InspectorMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testProcessShouldLogDebugInformationToConsole()
    {
        $this->expectOutputRegex('/<script>.+<\/script>/');

        $context = new Context();
        $middleware = new InspectorMiddleware();
        $next = new MiddlewareCallback();
        $presentationModel = new ProductPresentationModel();
        $view = ViewFactory::create($presentationModel);

        $middleware->process($view, $context, $next);
    }
}
