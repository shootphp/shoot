<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Inheritance;

use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class InheritanceTest extends IntegrationTestCase
{
    /** @var string */
    protected $templateDirectory = __DIR__ . '/Templates';

    /**
     * @return void
     */
    public function testShouldRenderBaseTemplateWithPlaceholders()
    {
        $output = $this->renderTemplate('base.twig');

        $this->assertContains('<title>base_title</title>', $output);
        $this->assertContains('<h1>base_title</h1>', $output);
        $this->assertContains('<p>base_footer</p>', $output);
    }

    /**
     * @return void
     */
    public function testShouldRenderChildTemplateWithOverriddenVariables()
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<title>page_title</title>', $output);
        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertContains('<p>page_content</p>', $output);
        $this->assertContains('<p>base_footer</p>', $output);
    }
}
