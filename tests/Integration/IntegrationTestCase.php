<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration;

use Exception;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shoot\Shoot\Installer;
use Shoot\Shoot\Middleware\PresenterMiddleware;
use Shoot\Shoot\Pipeline;
use Twig_Environment as Environment;
use Twig_Loader_Filesystem as FilesystemLoader;

abstract class IntegrationTestCase extends TestCase
{
    /** @var mixed */
    private $container = [];

    /** @var Pipeline */
    protected $pipeline;

    /** @var ServerRequestInterface|MockObject */
    protected $request;

    /** @var string */
    protected $templateDirectory = '';

    /** @var Environment */
    private $twig;

    protected function setUp(): void
    {
        if (empty($this->templateDirectory)) {
            throw new LogicException('Template directory is not set in ' . static::class);
        }

        $container = $this->createContainer();
        $pipeline = new Pipeline([new PresenterMiddleware($container)]);
        $installer = new Installer($pipeline);

        $loader = new FilesystemLoader([realpath($this->templateDirectory)]);
        $twig = new Environment($loader, ['cache' => false, 'strict_variables' => true]);
        $twig = $installer->install($twig);

        $this->pipeline = $pipeline;
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->twig = $twig;

        parent::setUp();
    }

    final protected function renderTemplate(string $template, array $variables = []): array
    {
        return $this->pipeline->withRequest($this->request, function () use ($template, $variables): array {
            $output = $this->twig->render($template, $variables);
            $output = trim($output);
            $output = explode(PHP_EOL, $output);
            $output = array_map('trim', $output);

            return $output;
        });
    }

    final protected function addToContainer(string $id, object $service): void
    {
        if (isset($this->container[$id])) {
            throw new LogicException("Service with ID '{$id}' already exists in container");
        }

        $this->container[$id] = $service;
    }

    private function createContainer(): ContainerInterface
    {
        return new class($this->container) implements ContainerInterface
        {
            private $container;

            public function __construct(array $container)
            {
                $this->container = $container;
            }

            public function get($id)
            {
                if (!$this->has($id)) {
                    throw new class extends Exception implements NotFoundExceptionInterface
                    {
                    };
                }

                return $this->container[$id] ?? new $id();
            }

            public function has($id): bool
            {
                return isset($this->container[$id]) || class_exists($id);
            }
        };
    }
}
