<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Models;

use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class ModelsTest extends IntegrationTestCase
{
    /** @var string */
    protected $templateDirectory = __DIR__ . '/Templates';

    /**
     * @return void
     */
    public function testVariablesDefinedInTheModelShouldBeAvailableToTheTemplate()
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertContains('<p>page_content</p>', $output);
    }

    /**
     * @return void
     */
    public function testDefiningMultipleModelsShouldThrowAnException()
    {
        $this->expectExceptionMessage('A presentation model has already been assigned');

        $this->renderTemplate('page_with_multiple_models.twig');
    }
}
