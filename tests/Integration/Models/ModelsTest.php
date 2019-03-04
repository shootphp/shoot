<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Models;

use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class ModelsTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->setTemplateDirectory(__DIR__ . '/Templates');

        parent::setUp();
    }

    public function testVariablesDefinedInTheModelShouldBeAvailableToTheTemplate(): void
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h1>page_title</h1>', $output);
        $this->assertContains('<p>page_content</p>', $output);
    }

    public function testDefiningMultipleModelsShouldThrowAnException(): void
    {
        $this->expectExceptionMessage('A presentation model has already been assigned');

        $this->renderTemplate('page_with_multiple_models.twig');
    }
}
