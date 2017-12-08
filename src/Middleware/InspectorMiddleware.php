<?php
declare(strict_types=1);

namespace Shoot\Shoot\Middleware;

use Shoot\Shoot\Context;
use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\MiddlewareInterface;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\View;

/**
 * The inspector shows you what templates, presentation models, presenters and variables were used to render a page by
 * logging this information to your browser console.
 */
final class InspectorMiddleware implements MiddlewareInterface
{
    /**
     * @param View     $view    The view to be processed by this middleware.
     * @param Context  $context The context in which to process the view.
     * @param callable $next    The next middleware to call
     *
     * @return View The processed view.
     */
    public function process(View $view, Context $context, callable $next): View
    {
        $this->script();
        $this->view($view);
        $this->scriptEnd();

        return $next($view);
    }

    /**
     * @param mixed  $value
     * @param string $label
     *
     * @return void
     */
    private function value($value, string $label = '')
    {
        if (is_scalar($value)) {
            $this->scalar($value, $label);
        } elseif (is_array($value)) {
            $this->iterable($value, $label);
        } elseif (is_object($value)) {
            $this->object($value, $label);
        }
    }

    /**
     * @param bool|float|int|string $scalar
     * @param string                $label
     *
     * @return void
     */
    private function scalar($scalar, string $label = '')
    {
        if (is_bool($scalar)) {
            $scalar = $scalar ? 'true' : 'false';
        } elseif (is_string($scalar)) {
            $scalar = "'{$this->escape($scalar)}'";
        }

        if ($label !== '') {
            $label = $this->escape($label);

            echo "console.log('%c{$label}','font-weight:bold',{$scalar});";
        } else {
            echo "console.log({$scalar});";
        }
    }

    /**
     * @param mixed[] $iterable
     * @param string  $label
     *
     * @return void
     */
    private function iterable(array $iterable, string $label = '')
    {
        if ($label === '') {
            $label = '...';
        }

        $this->group($label);

        foreach ($iterable as $key => $item) {
            if (!is_string($key)) {
                $key = '';
            }

            $this->value($item, $key);
        }

        $this->groupEnd();
    }

    /**
     * @param object $object
     * @param string $label
     *
     * @return void
     */
    private function object($object, string $label = '')
    {
        if ($object instanceof PresentationModel) {
            $this->presentationModel($object, $label);
        } elseif ($object instanceof View) {
            $this->view($object);
        } else {
            $this->iterable(get_object_vars($object), $label);
        }
    }

    /**
     * @param View $view
     *
     * @return void
     */
    private function view(View $view)
    {
        $this->group($view->getName(), false);
        $this->presentationModel($view->getPresentationModel(), 'presentationModel');
        $this->groupEnd();
    }

    /**
     * @param PresentationModel $presentationModel
     * @param string            $label
     *
     * @return void
     */
    private function presentationModel(PresentationModel $presentationModel, string $label = '')
    {
        if ($label === '') {
            $label = $presentationModel->getName();
        }

        $this->group($label, false);
        $this->value($presentationModel->getName(), 'name');

        if ($presentationModel instanceof HasPresenterInterface) {
            $this->value($presentationModel->getPresenter(), 'presenter');
        }

        $this->iterable($presentationModel->getVariables(), 'variables');
        $this->groupEnd();
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function escape(string $string): string
    {
        return addslashes($string);
    }

    /**
     * @param string $label
     * @param bool   $collapsed
     *
     * @return void
     */
    private function group(string $label, bool $collapsed = true)
    {
        $label = $this->escape($label);

        if ($collapsed) {
            echo "console.groupCollapsed('{$label}');";
        } else {
            echo "console.group('{$label}');";
        }
    }

    /**
     * @return void
     */
    private function groupEnd()
    {
        echo 'console.groupEnd();';
    }

    /**
     * @return void
     */
    private function script()
    {
        echo '<script>';
    }

    /**
     * @return void
     */
    private function scriptEnd()
    {
        echo '</script>';
    }
}
