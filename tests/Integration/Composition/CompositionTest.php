<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Composition;

use Shoot\Shoot\Tests\Integration\IntegrationTestCase;

final class CompositionTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->setTemplateDirectory(__DIR__ . '/Templates');

        parent::setUp();
    }

    public function testVariablesFilterShouldPassOnModelVariablesToIncludedTemplate(): void
    {
        $output = $this->renderTemplate('page.twig');

        $this->assertContains('<h2>item_label</h2>', $output);
    }
}
