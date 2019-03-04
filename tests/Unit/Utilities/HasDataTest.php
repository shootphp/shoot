<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Unit\Utilities;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;
use Shoot\Shoot\Utilities\HasDataTrait;

final class HasDataTest extends TestCase
{
    /** @var PresentationModel */
    private $presentationModel;

    /** @var PresenterInterface */
    private $presenter;

    /** @var ServerRequestInterface|MockObject */
    private $request;

    protected function setUp(): void
    {
        $this->presenter = new class implements PresenterInterface
        {
            use HasDataTrait;

            public function present(
                ServerRequestInterface $request,
                PresentationModel $presentationModel
            ): PresentationModel {
                return $presentationModel->withVariables([
                    'has_data' => $this->hasData($presentationModel) ? 'has_data' : 'does_not_have_data',
                ]);
            }
        };

        $this->presentationModel = new class extends PresentationModel
        {
            protected $has_data = '';

            protected $variable = '';
        };

        $this->request = $this->createMock(ServerRequestInterface::class);

        parent::setUp();
    }

    public function testHasDataShouldReturnFalseForEmptyPresentationModels(): void
    {
        $presentationModel = $this->presenter->present($this->request, $this->presentationModel);

        $this->assertEquals('does_not_have_data', $presentationModel->getVariable('has_data'));
    }

    public function testHasDataShouldReturnTrueForNonEmptyPresentationModels(): void
    {
        $presentationModel = $this->presenter->present(
            $this->request,
            $this->presentationModel->withVariables(['variable' => 'variable'])
        );

        $this->assertEquals('has_data', $presentationModel->getVariable('has_data'));
    }
}
