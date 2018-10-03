<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Embedding;

use PHPUnit\Framework\MockObject\MockObject;
use Shoot\Shoot\PresenterInterface;
use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class EmbeddingTest extends IntegrationTestCase
{
    /** @var PresenterInterface|MockObject */
    private $pagePresenter;

    /** @var string */
    protected $templateDirectory = __DIR__ . '/Templates';

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->pagePresenter = $this->createMock(PresenterInterface::class);
        $this->pagePresenter
            ->method('present')
            ->will($this->returnArgument(1));

        $this->addToContainer('PagePresenter', $this->pagePresenter);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testEmbeddedTemplatesShouldHaveTheirPresentersInvoked()
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<li><a href="/">Home</a></li>', $output);
    }

    /**
     * @return void
     */
    public function testOverriddenBlocksShouldReceiveVariablesFromParent()
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertContains('<p>page_content</p>', $output);
    }

    /**
     * @return void
     */
    public function testEmbeddedTemplatesShouldReceiveVariablesPassedAsArguments()
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<main class="main--overriden">', $output);
    }

    /**
     * @return void
     */
    public function testPresentersShouldOnlyBeCalledOnce()
    {
        $this->pagePresenter
            ->expects($this->once())
            ->method('present');

        $this->renderTemplate('page.twig');
    }
}
