<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\ErrorSuppression;

use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class ErrorSuppressionTest extends IntegrationTestCase
{
    /** @var string */
    protected $templateDirectory = __DIR__ . '/Templates';

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testIncludedTemplateShouldThrowAnException()
    {
        $this->expectExceptionMessage('item_exception');

        $this->renderTemplate('item.twig');
    }

    /**
     * @return void
     */
    public function testOptionalBlocksShouldDiscardTheirContentsOnRuntimeExceptions()
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertNotContains('<!-- hidden -->', $output);
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
            ->with('throw_logic_exception')
            ->willReturn('y');

        $this->renderTemplate('page.twig');
    }
}
