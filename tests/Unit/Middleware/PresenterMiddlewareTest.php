<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;

final class PresenterMiddlewareTest extends TestCase
{
    /** @var MiddlewareInterface */
    private $middleware;

    /** @var callable */
    private $next;

    /** @var ServerRequestInterface|MockObject */
    private $request;

    /** @var View */
    private $view;

    /**
     * @return void
     */
    protected function setUp()
    {
        $presenter = new class implements PresenterInterface
        {
            public function present(
                ServerRequestInterface $request,
                PresentationModel $presentationModel
            ): PresentationModel {
                return $presentationModel->withVariables(['variable' => 'variable']);
            }
        };

        /** @var ContainerInterface|MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->with('MockPresenter')
            ->willReturn($presenter);

        $this->middleware = new PresenterMiddleware($container);

        $this->next = function (View $view): View {
            return $view;
        };

        $this->request = $this->createMock(ServerRequestInterface::class);

        $this->view = ViewFactory::create();

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testShouldLoadPresenterIfPresentationModelHasPresenter()
    {
        $this->assertEmpty($this->view->getPresentationModel()->getVariable('variable', ''));

        $view = $this->middleware->process($this->view, $this->request, $this->next);

        $this->assertNotEmpty($view->getPresentationModel()->getVariable('variable', ''));
    }
}
