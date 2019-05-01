<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use stdClass;

final class ViewTest extends TestCase
{
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
