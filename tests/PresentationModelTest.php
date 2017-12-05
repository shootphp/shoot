<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\ProductPresentationModel;

final class PresentationModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testGenericPresentationModelsShouldOnlyAllowStringVariableNames()
    {
        $presentationModel = new PresentationModel([
            'some_variable' => 'Expect this to be set',
            0 => 'But not this',
        ]);

        $variables = $presentationModel->getVariables();

        $this->assertCount(1, $variables);
        $this->assertArrayHasKey('some_variable', $variables);
    }

    /**
     * @return void
     */
    public function testSpecificPresentationModelsShouldOnlySetDefinedVariables()
    {
        $presentationModel = new ProductPresentationModel([
            'product_name' => 'Expect this to be set',
            'some_other_variable' => 'But not this'
        ]);

        $variables = $presentationModel->getVariables();

        $this->assertArrayHasKey('product_name', $variables);
        $this->assertArrayNotHasKey('some_other_variable', $variables);
    }
}
