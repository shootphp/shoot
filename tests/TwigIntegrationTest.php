<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Extension;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\Tests\Mocks\ContainerStub;
use Twig_Environment as Environment;
use Twig_Error_Runtime;
use Twig_Loader_Filesystem as FilesystemLoader;

final class TwigIntegrationTest extends TestCase
{
    /** @var Pipeline */
    private $pipeline;

    /** @var ServerRequestInterface|MockObject */
    private $request;

    /** @var Environment */
    private $twig;

    /**
     * @return void
     */
    protected function setUp()
    {
        $container = new ContainerStub();
        $pipeline = new Pipeline([new PresenterMiddleware($container)]);
        $extension = new Extension($pipeline);

        $loader = new FilesystemLoader([realpath(__DIR__ . '/Fixtures/Templates')]);
        $twig = new Environment($loader, ['cache' => false, 'strict_variables' => true]);
        $twig->addExtension($extension);

        $this->pipeline = $pipeline;
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->twig = $twig;
    }

    /**
     * @return void
     */
    public function testShouldRenderASingleModel()
    {
        $output = $this->renderTemplate('item.twig');

        $this->assertSame([
            '## item',
            'description',
        ], $output);
    }

    /**
     * @return void
     */
    public function testShouldRenderAListOfModels()
    {
        $output = $this->renderTemplate('item_list.twig');

        $this->assertSame([
            '# items',
            '## item 1',
            'description',
            '## item 2',
            'description',
            '## item 3',
            'description',
        ], $output);
    }

    /**
     * @return void
     */
    public function testAssigningMultipleModelsToAViewShouldThrowAnException()
    {
        $this->expectExceptionMessage('model has already been assigned');

        $this->renderTemplate('multiple_models.twig');
    }

    /**
     * @return void
     */
    public function testEmbeddedTemplatesShouldReceiveAllVariables()
    {
        $output = $this->renderTemplate('has_embedded_template.twig');

        $this->assertSame([
            '# title: item',
            'description',
        ], $output);
    }

    /**
     * @return void
     */
    public function testOptionalBlocksShouldBeHiddenIfTheyFail()
    {
        $output = $this->renderTemplate('optional_runtime_exception.twig');

        $this->assertContains('header', $output);
        $this->assertNotContains('should not be rendered', $output);
        $this->assertContains('footer', $output);
    }

    /**
     * @return void
     */
    public function testOptionalBlocksShouldNotSuppressUnknownVariables()
    {
        $this->expectException(Twig_Error_Runtime::class);

        $this->renderTemplate('optional_unknown_variable.twig');
    }

    /**
     * @return void
     */
    public function testNestedOptionalBlocksShouldNotAffectedParents()
    {
        $output = $this->renderTemplate('optional_nested.twig');

        $this->assertContains('header', $output);
        $this->assertNotContains('should not be rendered', $output);
        $this->assertContains('not affected', $output);
        $this->assertContains('footer', $output);
    }

    /**
     * @param string $template
     *
     * @return string[]
     */
    private function renderTemplate(string $template): array
    {
        return $this->pipeline->withRequest($this->request, function () use ($template): array {
            $output = $this->twig->render($template);
            $output = trim($output);
            $output = explode(PHP_EOL, $output);
            $output = array_map('trim', $output);

            return $output;
        });
    }
}
