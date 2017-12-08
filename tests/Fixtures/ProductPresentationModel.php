<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\HasPresenterInterface;
use Shoot\Shoot\PresentationModel;

final class ProductPresentationModel extends PresentationModel implements HasPresenterInterface
{
    /** @var bool */
    protected $on_stock = false;

    /** @var string */
    protected $product_name = '';

    /** @var int */
    protected $stock_quantity = 0;

    /**
     * @return string
     */
    public function getPresenter(): string
    {
        return ProductPresenter::class;
    }
}
