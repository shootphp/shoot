<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\ContextInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\View;

final class Middleware implements MiddlewareInterface
{
    /** @var callable */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable = null)
    {
        $this->callable = $callable;
    }

    /**
     * @param View             $view
     * @param ContextInterface $context
     * @param callable         $next
     *
     * @return View
     */
    public function process(View $view, ContextInterface $context, callable $next = null): View
    {
        if ($next === null) {
            $next = function (View $view): View {
                return $view;
            };
        }

        return call_user_func($this->callable, $view, $context, $next) ?? $view;
    }
}
