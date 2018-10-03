<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Integration\Composition\Models;

use Shoot\Shoot\PresentationModel;

final class Item extends PresentationModel
{
    /** @var string */
    protected $label = 'item_label';
}
