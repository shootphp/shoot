<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\Middleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Shoot\Shoot\View;
use Twig_Filter as TwigFilter;
use Twig_Test as TwigTest;

final class PipelineTest extends TestCase
{
    /** @var ServerRequestInterface */
    private $request;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->request = $this->prophesize(ServerRequestInterface::class)->reveal();
    }

    /**
     * @param View $view
     * @param MiddlewareInterface[] $middleware
     *
     * @return mixed
     */
    private function passThroughPipeline(View $view, array $middleware = [])
    {
        $pipeline = new Pipeline($middleware);

        return $pipeline->withRequest($this->request, function () use ($pipeline, $view) {
            $pipeline->process($view);
        });
    }

    /**
     * @return void
     */
    public function testProcessShouldCallMiddleware()
    {
        $wasCalled = false;

        $middleware = new Middleware(function () use (&$wasCalled) {
            $wasCalled = true;
        });

        $view = ViewFactory::create();

        $this->passThroughPipeline($view, [$middleware]);

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

        $pipeline->withRequest($this->request, function () use ($pipeline, $view) {
            $pipeline->process($view);
        });

        $pipeline->process($view);

        $this->assertTrue($wasCalled);
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
