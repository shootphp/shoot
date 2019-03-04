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

    protected function setUp(): void
    {
        $this->pagePresenter = $this->createMock(PresenterInterface::class);
        $this->pagePresenter
            ->method('present')
            ->will($this->returnArgument(1));

        $this->addToContainer('PagePresenter', $this->pagePresenter);
        $this->setTemplateDirectory(__DIR__ . '/Templates');

        parent::setUp();
    }

    public function testEmbeddedTemplatesShouldHaveTheirPresentersInvoked(): void
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<li><a href="/">Home</a></li>', $output);
    }

    public function testOverriddenBlocksShouldReceiveVariablesFromParent(): void
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<title>page_title</title>', $output);
        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertContains('<p>page_content</p>', $output);
    }

    public function testEmbeddedTemplatesShouldReceiveVariablesPassedAsArguments(): void
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<main class="main--overriden">', $output);
    }

    public function testPresentersShouldOnlyBeCalledOnce(): void
    {
        $this->pagePresenter
            ->expects($this->once())
            ->method('present');

        $this->renderTemplate('page.twig');
    }
}
