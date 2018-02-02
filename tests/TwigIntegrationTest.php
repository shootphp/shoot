<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\Tests\Fixtures\Container;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as FilesystemLoader;

final class TwigIntegrationTest extends TestCase
{
    /** @var Environment */
    private $twig;

    /**
     * @return void
     */
    protected function setUp()
    {
        $container = new Container();
        $middleware = [new PresenterMiddleware($container)];
        $pipeline = new Pipeline($middleware);
        $loader = new FilesystemLoader([realpath(__DIR__ . '/Fixtures/Templates')]);
        $this->twig = new Environment($loader, ['cache' => false, 'strict_variables' => true]);
        $this->twig->addExtension($pipeline);
    }

    /**
     * @return void
     */
    public function testRenderSingleModel()
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
    public function testRenderListOfModels()
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

        $this->renderTemplate('duplicate_models.twig');
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
     * @param string $template
     *
     * @return string[]
     */
    private function renderTemplate(string $template): array
    {
        $output = $this->twig->render($template);
        $output = trim($output);
        $output = explode(PHP_EOL, $output);
        $output = array_map('trim', $output);

        return $output;
    }
}
