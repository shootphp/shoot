<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\Pipeline;
use Shoot\Shoot\Tests\Fixtures\Container;
use Twig_Environment as Environment;
use Twig_Error as Error;
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
        $this->twig = new Environment($loader, ['strict_variables' => true]);
        $this->twig->addExtension($pipeline);
    }

    /**
     * @throws Error
     *
     * @return void
     */
    public function testRenderSingleModel()
    {
        $output = $this->renderTemplate('product.twig');

        $this->assertSame([
            '## ACME Anvil',
            'We still have 3 units on stock!'
        ], $output);
    }

    /**
     * @throws Error
     *
     * @return void
     */
    public function testRenderListOfModels()
    {
        $output = $this->renderTemplate('product_list.twig');

        $this->assertSame([
            '# Products',
            '## ACME Piano',
            'We still have 6 units on stock!',
            '## ACME Rocket',
            'Sorry, all out of stock.',
            '## ACME Hammer',
            'We still have 17 units on stock!'
        ], $output);
    }

    /**
     * @throws Error
     *
     * @return void
     */
    public function testAssigningMultipleModelsToAViewShouldThrowAnException()
    {
        $this->expectExceptionMessage('model has already been assigned');

        $this->renderTemplate('duplicate_models.twig');
    }

    /**
     * @param string $template
     *
     * @throws Error
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
