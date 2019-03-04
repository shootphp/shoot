<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\PresentationModel;
use TypeError;

final class PresentationModelTest extends TestCase
{
    public function testGenericPresentationModelsShouldOnlyAllowStringVariableNames(): void
    {
        $this->expectException(TypeError::class);

        new PresentationModel([
            0 => 'A non-string key causes a type error to be thrown',
        ]);
    }

    public function testSpecificPresentationModelsShouldOnlySetDefinedVariables(): void
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

    public function testGetVariableShouldReturnValueOfVariable(): void
    {
        $presentationModel = new PresentationModel([
            'name' => 'name',
        ]);

        $this->assertSame('name', $presentationModel->getVariable('name', 'default'));
    }

    public function testGetVariableShouldReturnDefaultValueIfVariableDoesNotExist(): void
    {
        $presentationModel = new PresentationModel();

        $this->assertSame('default', $presentationModel->getVariable('does_not_exist', 'default'));
    }
}
