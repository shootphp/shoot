<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\ErrorSuppression;

use Shoot\Shoot\Middleware\SuppressionMiddleware;
use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class ErrorSuppressionTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->addMiddleware(new SuppressionMiddleware());
        $this->setTemplateDirectory(__DIR__ . '/Templates');

        parent::setUp();
    }

    public function testTemplateShouldRenderIfNoExceptionIsThrown(): void
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertContains('<!-- before -->', $output);
        $this->assertContains('<p>item</p>', $output);
        $this->assertContains('<!-- after -->', $output);
        $this->assertContains('<p>page_footer</p>', $output);
    }

    public function testIncludedTemplateShouldThrowAnException(): void
    {
        $this->expectExceptionMessage('item_exception');

        $this->getRequestMock()
            ->method('getAttribute')
            ->will($this->returnValueMap([
                ['throw_logic_exception', 'n', 'n'],
                ['throw_runtime_exception', 'n', 'y'],
            ]));

        $this->renderTemplate('item.twig');
    }

    public function testOptionalBlocksShouldDiscardTheirContentsOnRuntimeExceptions(): void
    {
        $this->getRequestMock()
            ->method('getAttribute')
            ->will($this->returnValueMap([
                ['throw_logic_exception', 'n', 'n'],
                ['throw_runtime_exception', 'n', 'y'],
            ]));

        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertNotContains('<!-- before -->', $output);
        $this->assertNotContains('<p>item</p>', $output);
        $this->assertNotContains('<!-- after -->', $output);
        $this->assertContains('<p>page_footer</p>', $output);
    }

    public function testOptionalBlocksShouldNotSuppressLogicExceptions(): void
    {
        $this->expectExceptionMessage('Variable "unknown_variable" does not exist');

        $this->getRequestMock()
            ->method('getAttribute')
            ->will($this->returnValueMap([
                ['throw_logic_exception', 'n', 'y'],
                ['throw_runtime_exception', 'n', 'n'],
            ]));

        $this->renderTemplate('page.twig');
    }
}
