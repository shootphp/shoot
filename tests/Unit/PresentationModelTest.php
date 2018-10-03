<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\PresentationModel;
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
        $presentationModel = new class ([
            'name' => 'name',
            'non_existing_variable' => 'non_existing_variable',
        ]) extends PresentationModel
        {
            protected $name = '';
        };

        $variables = $presentationModel->getVariables();

        $this->assertArrayHasKey('name', $variables);
        $this->assertArrayNotHasKey('non_existing_variable', $variables);
    }

    /**
     * @return void
     */
    public function testGetVariableShouldReturnValueOfVariable()
    {
        $presentationModel = new PresentationModel([
            'name' => 'name',
        ]);

        $this->assertSame('name', $presentationModel->getVariable('name', 'default'));
    }

    /**
     * @return void
     */
    public function testGetVariableShouldReturnDefaultValueIfVariableDoesNotExist()
    {
        $presentationModel = new PresentationModel();

        $this->assertSame('default', $presentationModel->getVariable('does_not_exist', 'default'));
    }
}
