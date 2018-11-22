<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\ErrorSuppression;

use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class ErrorSuppressionTest extends IntegrationTestCase
{
    /** @var string */
    protected $templateDirectory = __DIR__ . '/Templates';

    public function testTemplateShouldRenderIfNoExceptionIsThrown()
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertContains('<!-- before -->', $output);
        $this->assertContains('<p>item</p>', $output);
        $this->assertContains('<!-- after -->', $output);
        $this->assertContains('<p>page_footer</p>', $output);
    }

    /**
     * @return void
     */
    public function testIncludedTemplateShouldThrowAnException()
    {
        $this->expectExceptionMessage('item_exception');

        $this->request
            ->method('getAttribute')
            ->will($this->returnValueMap([
                ['throw_logic_exception', 'n', 'n'],
                ['throw_runtime_exception', 'n', 'y'],
            ]));

        $this->renderTemplate('item.twig');
    }

    /**
     * @return void
     */
    public function testOptionalBlocksShouldDiscardTheirContentsOnRuntimeExceptions()
    {
        $this->request
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

    /**
     * @return void
     */
    public function testOptionalBlocksShouldNotSuppressLogicExceptions()
    {
        $this->expectExceptionMessage('Variable "unknown_variable" does not exist');

        $this->request
            ->method('getAttribute')
            ->will($this->returnValueMap([
                ['throw_logic_exception', 'n', 'y'],
                ['throw_runtime_exception', 'n', 'n'],
            ]));

        $this->renderTemplate('page.twig');
    }
}
