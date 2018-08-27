<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\Tests\Mocks\ContainerStub;
use Shoot\Shoot\View;

final class PresenterMiddlewareTest extends TestCase
{
    /**
     * @return void
     */
    public function testShouldLoadPresenterIfPresentationModelHasPresenter()
    {
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $middleware = new PresenterMiddleware(new ContainerStub());
        $view = ViewFactory::create();
        $next = function (View $view): View {
            return $view;
        };

        $this->assertEmpty($view->getPresentationModel()->getVariable('name', ''));

        $view = $middleware->process($view, $request, $next);

        $this->assertNotEmpty($view->getPresentationModel()->getVariable('name', ''));
    }
}
