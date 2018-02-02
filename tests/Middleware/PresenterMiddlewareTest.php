<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\Tests\Fixtures\Container;
use Shoot\Shoot\Tests\Fixtures\Item;
use Shoot\Shoot\Tests\Fixtures\MiddlewareCallback;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

final class PresenterMiddlewareTest extends TestCase
{
    /** @var MiddlewareInterface */
    private $middleware;

    /** @var callable */
    private $next;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->middleware = new PresenterMiddleware(new Container());
        $this->next = new MiddlewareCallback();
    }

    /**
     * @return void
     */
    public function testProcessShouldNotLoadPresenterIfPresentationModelHasData()
    {
        $presentationModel = new Item(['name' => 'item']);

        $view = ViewFactory::create($presentationModel);

        $view = $this->middleware->process($view, null, $this->next);

        $this->assertSame($presentationModel, $view->getPresentationModel());
    }

    /**
     * @return void
     */
    public function testProcessShouldLoadPresenterIfPresentationModelHasPresenterAndDoesNotHaveData()
    {
        $presentationModel = new Item();

        $view = ViewFactory::create($presentationModel);

        $this->assertEmpty($view->getPresentationModel()->getVariables()['name']);

        $view = $this->middleware->process($view, null, $this->next);

        $this->assertSame('item', $view->getPresentationModel()->getVariables()['name']);
    }
}
