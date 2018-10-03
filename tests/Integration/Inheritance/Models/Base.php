<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Inheritance\Models;

use Shoot\Shoot\PresentationModel;

final class Base extends PresentationModel
{
    /** @var string */
    protected $footer = 'base_footer';

    /** @var string */
    protected $title = 'base_title';
}
