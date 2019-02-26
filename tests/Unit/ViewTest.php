<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;
use stdClass;

final class ViewTest extends TestCase
{
    public function testShouldNotAllowEmptyNames(): void
    {
        $presentationModel = new PresentationModel();
        $callback = function () {
            // noop
        };

        $this->expectException(InvalidArgumentException::class);

        new View('', $presentationModel, $callback);
    }

    public function testShouldExecuteCallback(): void
    {
        /** @var callable|MockObject $callback */
        $callback = $this
            ->getMockBuilder(stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callback
            ->expects($this->once())
            ->method('__invoke');

        $view = ViewFactory::createWithCallback($callback);
        $view->render();
    }
}
