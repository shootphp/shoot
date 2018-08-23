<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\ViewCallback;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;

final class ViewTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstructorShouldNotAllowEmptyNames()
    {
        $this->expectException(InvalidArgumentException::class);

        $name = '';
        $presentationModel = new PresentationModel();
        $callback = new ViewCallback();

        new View($name, $presentationModel, $callback);
    }

    /**
     * @return void
     */
    public function testRenderShouldExecuteCallback()
    {
        $wasCalled = false;

        $callback = function () use (&$wasCalled) {
            $wasCalled = true;
        };

        $view = ViewFactory::create(null, $callback);

        $view->render();

        $this->assertTrue($wasCalled);
    }
}
