<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\Tests\Fixtures\ProductPresentationModel;
use TypeError;

final class PresentationModelTest extends TestCase
{
    /**
     * @return void
     */
    public function testGenericPresentationModelsShouldOnlyAllowStringVariableNames()
    {
        $this->expectException(TypeError::class);

        new PresentationModel([
            0 => 'A non-string key causes a type error to be thrown',
        ]);
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
