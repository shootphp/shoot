<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\MissingRequestException;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\Tests\Fixtures\Middleware;
use Shoot\Shoot\Tests\Fixtures\ViewFactory;

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
     * @return void
     */
    public function testProcessShouldCallMiddleware()
    {
        $wasCalled = false;

        $pipeline = new Pipeline([
            new Middleware(function () use (&$wasCalled) {
                $wasCalled = true;
            }),
        ]);

        $view = ViewFactory::create();

        $pipeline->withRequest($this->request, function () use ($pipeline, $view) {
            $pipeline->process($view);
        });

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

        $this->assertTrue($wasCalled);
    }

    /**
     * @return void
     */
    public function testProcessShouldThrowIfNoRequestWasSet()
    {
        $this->expectException(MissingRequestException::class);

        $pipeline = new Pipeline();

        $view = ViewFactory::create();

        $pipeline->process($view);
    }
}
