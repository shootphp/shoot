<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\Context;
use Shoot\Shoot\HasDataTrait;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;

final class ProductPresenter implements PresenterInterface
{
    use HasDataTrait;

    /**
     * @param Context           $context
     * @param PresentationModel $presentationModel
     *
     * @return PresentationModel
     */
    public function present(Context $context, PresentationModel $presentationModel): PresentationModel
    {
        if ($this->hasData($presentationModel)) {
            return $presentationModel;
        }

        return $presentationModel->withVariables([
            'product_name' => 'ACME Anvil',
            'stock_quantity' => 3,
            'on_stock' => true
        ]);
    }
}
