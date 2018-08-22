<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Extension;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\Middleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;
use Twig_Filter as TwigFilter;
use Twig_Test as TwigTest;

final class ExtensionTest extends TestCase
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

    public function testExtensionShouldDelegateProcessingToPipeline()
    {
        $wasCalled = false;

        $pipeline = new Pipeline([
            new Middleware(function () use (&$wasCalled) {
                $wasCalled = true;
            }),
        ]);

        $extension = new Extension($pipeline);

        $view = ViewFactory::create();

        $pipeline->withRequest($this->request, function () use ($extension, $view) {
            $extension->process($view);
        });

        $this->assertTrue($wasCalled);
    }

    /**
     * @return void
     */
    public function testExtensionShouldIncludeVariablesFilter()
    {
        $extension = new Extension(new Pipeline());
        $presentationModel = new PresentationModel(['variable' => 'value']);

        /** @var TwigFilter[] $filters */
        $filters = array_filter($extension->getFilters(), function (TwigFilter $filter): bool {
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
        $extension = new Extension(new Pipeline());
        $presentationModel = new PresentationModel();

        /** @var TwigTest[] $tests */
        $tests = array_filter($extension->getTests(), function (TwigTest $test): bool {
            return $test->getName() === 'model';
        });

        $this->assertCount(1, $tests);

        $test = array_shift($tests);
        $callback = $test->getCallable();

        $this->assertTrue($callback($presentationModel));
    }
}
