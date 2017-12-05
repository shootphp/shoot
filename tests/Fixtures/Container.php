<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Exception;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Container implements ContainerInterface
{
    /**
     * @param string $id
     *
     * @throws Exception|NotFoundExceptionInterface
     *
     * @return mixed
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new class extends Exception implements NotFoundExceptionInterface
            {
            };
        }

        return new $id();
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id): bool
    {
        return class_exists($id);
    }
}
