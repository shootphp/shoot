<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Context;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\Tests\Fixtures\Container;
use Shoot\Shoot\Tests\Fixtures\MiddlewareCallback;
use Shoot\Shoot\Tests\Fixtures\ProductPresentationModel;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

final class PresenterMiddlewareTest extends TestCase
{
    /** @var Context */
    private $context;

    /** @var MiddlewareInterface */
    private $middleware;

    /** @var callable */
    private $next;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->context = new Context();
        $this->middleware = new PresenterMiddleware(new Container());
        $this->next = new MiddlewareCallback();
    }

    /**
     * @return void
     */
    public function testProcessShouldNotLoadPresenterIfPresentationModelHasData()
    {
        $presentationModel = new ProductPresentationModel([
            'product_name' => 'ACME Anvil'
        ]);

        $view = ViewFactory::create($presentationModel);

        $view = $this->middleware->process($view, $this->context, $this->next);

        $this->assertSame($presentationModel, $view->getPresentationModel());
    }

    /**
     * @return void
     */
    public function testProcessShouldLoadPresenterIfPresentationModelHasPresenterAndDoesNotHaveData()
    {
        $presentationModel = new ProductPresentationModel();

        $view = ViewFactory::create($presentationModel);

        $this->assertEmpty($view->getPresentationModel()->getVariables()['product_name']);

        $view = $this->middleware->process($view, $this->context, $this->next);

        $this->assertSame('ACME Anvil', $view->getPresentationModel()->getVariables()['product_name']);
    }
}
