<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Extension;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Twig_Filter as TwigFilter;
use Twig_Test as TwigTest;

final class ExtensionTest extends TestCase
{
    public function testExtensionShouldDelegateProcessingToPipeline(): void
    {
        /** @var ServerRequestInterface|MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $view = ViewFactory::create();

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware
            ->expects($this->once())
            ->method('process')
            ->with($this->equalTo($view))
            ->willReturn($view);

        $pipeline = new Pipeline([$middleware]);
        $extension = new Extension($pipeline);

        $pipeline->withRequest($request, function () use ($extension, $view) {
            $extension->process($view);
        });
    }

    public function testExtensionShouldIncludeVariablesFilter(): void
    {
        $extension = new Extension(new Pipeline());
        $presentationModel = new PresentationModel(['variable' => 'variable']);

        /** @var TwigFilter[] $filters */
        $filters = array_filter($extension->getFilters(), function (TwigFilter $filter): bool {
            return $filter->getName() === 'variables';
        });

        $this->assertCount(1, $filters);

        list($filter) = $filters;
        $callback = $filter->getCallable();

        /** @var mixed[] $variables */
        $variables = $callback($presentationModel);

        $this->assertIsArray($variables);
        $this->assertCount(1, $variables);
        $this->assertArrayHasKey('variable', $variables);
    }

    public function testExtensionShouldIncludeModelTest(): void
    {
        $extension = new Extension(new Pipeline());
        $presentationModel = new PresentationModel();

        /** @var TwigTest[] $tests */
        $tests = array_filter($extension->getTests(), function (TwigTest $test): bool {
            return $test->getName() === 'model';
        });

        $this->assertCount(1, $tests);

        list($test) = $tests;
        $callback = $test->getCallable();

        $this->assertTrue($callback($presentationModel));
    }
}
