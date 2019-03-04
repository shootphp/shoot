<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit\Middleware;

use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Middleware\SuppressionMiddleware;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\SuppressedException;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;

final class SuppressionMiddlewareTest extends TestCase
{
    /** @var MiddlewareInterface */
    private $middleware;

    /** @var callable */
    private $next;

    /** @var ServerRequestInterface|MockObject */
    private $request;

    protected function setUp(): void
    {
        $this->middleware = new SuppressionMiddleware();

        $this->next = function (View $view): View {
            $view->render();

            return $view;
        };

        $this->request = $this->createMock(ServerRequestInterface::class);

        parent::setUp();
    }

    public function testShouldCatchSuppressedExceptionAndAssignToView(): void
    {
        $view = ViewFactory::createWithCallback(function () {
            throw new SuppressedException(new Exception());
        });

        $this->assertFalse($view->hasSuppressedException());

        $view = $this->middleware->process($view, $this->request, $this->next);

        $this->assertTrue($view->hasSuppressedException());
    }

    public function testShouldIgnoreAllOtherExceptions(): void
    {
        $view = ViewFactory::createWithCallback(function () {
            throw new Exception();
        });

        $this->expectException(Exception::class);

        $this->middleware->process($view, $this->request, $this->next);
    }
}
