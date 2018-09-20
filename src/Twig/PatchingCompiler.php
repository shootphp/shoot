<?php
declare(strict_types=1);

namespace Shoot\Shoot\Twig;

use Twig_Compiler as Compiler;

/**
 * This compiler patches a few crucial lines in some core Twig classes. It allows Shoot to be used with extend, embed,
 * and blocks. Out of all hacks to make that work, this one seemed to be the most straightforward.
 *
 * @internal
 */
final class PatchingCompiler extends Compiler
{
    /**
     * @param mixed $string
     *
     * @return Compiler
     */
    public function raw($string)
    {
        return parent::raw($this->patch($string));
    }

    /**
     * @param mixed[] ...$strings
     *
     * @return Compiler
     */
    public function write(...$strings)
    {
        $strings = array_map([$this, 'patch'], $strings);

        return parent::write(...$strings);
    }

    /**
     * @param mixed $string
     *
     * @return mixed
     */
    private function patch($string)
    {
        static $patterns = [
            '/(->display\()\$context(, array_merge\(\$this->blocks, \$blocks\)\);\n)/',
            '/(\$this->displayBlock\(\'[^\']+\', )\$context(, \$blocks\);\n)/',
        ];

        return !is_string($string) ? $string : preg_replace($patterns, '$1array_merge($originalContext ?? [], $context)$2', $string);
    }
}
