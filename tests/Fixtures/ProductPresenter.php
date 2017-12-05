<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\Context;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;

final class ProductPresenter implements PresenterInterface
{
    /**
     * @param Context           $context
     * @param PresentationModel $presentationModel
     *
     * @return PresentationModel
     */
    public function present(Context $context, PresentationModel $presentationModel): PresentationModel
    {
        return $presentationModel->withVariables([
            'product_name' => 'ACME Anvil',
            'stock_quantity' => 3,
            'on_stock' => true
        ]);
    }
}
