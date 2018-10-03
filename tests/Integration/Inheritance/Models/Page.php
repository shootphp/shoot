<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Inheritance\Models;

use Shoot\Shoot\PresentationModel;

final class Page extends PresentationModel
{
    /** @var string */
    protected $content = 'page_content';

    /** @var string */
    protected $title = 'page_title';
}
