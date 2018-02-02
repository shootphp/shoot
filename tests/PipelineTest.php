<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\Middleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;
use Twig_Filter as TwigFilter;
use Twig_Test as TwigTest;

final class PipelineTest extends TestCase
{
    /**
     * @return void
     */
    public function testProcessShouldCallMiddleware()
    {
        $wasCalled = false;

        $middleware = new Middleware(function () use (&$wasCalled) {
            $wasCalled = true;
        });

        $pipeline = new Pipeline([$middleware]);

        $view = ViewFactory::create();

        $pipeline->process($view);

        $this->assertTrue($wasCalled);
    }

    /**
     * @return void
     */
    public function testProcessShouldRenderView()
    {
        $wasCalled = false;

        $pipeline = new Pipeline();

        $view = ViewFactory::create(null, function () use (&$wasCalled) {
            $wasCalled = true;
        });

        $pipeline->process($view);

        $this->assertTrue($wasCalled);
    }

    /**
     * @return void
     */
    public function testWithContextShouldClearContext()
    {
        $hadContext = false;

        $view = ViewFactory::create();

        $middleware = new Middleware(function (View $view, $context) use (&$hadContext) {
            $hadContext = ($context['string_attribute'] ?? '') !== '';
        });

        $pipeline = new Pipeline([$middleware]);
        $context = ['string_attribute' => 'value'];

        $pipeline->withContext($context, function () use ($pipeline, $view) {
            $pipeline->process($view);
        });

        $this->assertTrue($hadContext);

        $pipeline->process($view);

        $this->assertFalse($hadContext);
    }

    /**
     * @return void
     */
    public function testExtensionShouldIncludeVariablesFilter()
    {
        $pipeline = new Pipeline();
        $presentationModel = new PresentationModel(['variable' => 'value']);

        /** @var TwigFilter[] $filters */
        $filters = array_filter($pipeline->getFilters(), function (TwigFilter $filter): bool {
            return $filter->getName() === 'variables';
        });

        $this->assertCount(1, $filters);

        $filter = array_shift($filters);
        $callback = $filter->getCallable();

        /** @var mixed[] $variables */
        $variables = $callback($presentationModel);

        $this->assertInternalType('array', $variables);
        $this->assertCount(1, $variables);
        $this->assertArrayHasKey('variable', $variables);
    }

    /**
     * @return void
     */
    public function testExtensionShouldIncludeModelTest()
    {
        $pipeline = new Pipeline();
        $presentationModel = new PresentationModel();

        /** @var TwigTest[] $tests */
        $tests = array_filter($pipeline->getTests(), function (TwigTest $test): bool {
            return $test->getName() === 'model';
        });

        $this->assertCount(1, $tests);

        $test = array_shift($tests);
        $callback = $test->getCallable();

        $this->assertTrue($callback($presentationModel));
    }
}
