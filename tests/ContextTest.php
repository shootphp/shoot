<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests;

use PHPUnit\Framework\TestCase;
use Shoot\Shoot\Context;

final class ContextTest extends TestCase
{
    /** @var Context */
    private $context;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->context = new Context([
            'null_attribute' => null,
            'string_attribute' => 'value',
        ]);
    }

    /**
     * @return void
     */
    public function testGetAttributeShouldReturnGivenDefaultValueIfAttributeDoesNotExist()
    {
        $this->assertSame('default', $this->context->getAttribute('non_existing_attribute', 'default'));
    }

    /**
     * @return void
     */
    public function testGetAttributeShouldReturnValueIfAttributeExists()
    {
        $this->assertSame('value', $this->context->getAttribute('string_attribute'));
    }
}
