<?php
declare(strict_types=1);

namespace Shoot\Shoot\Tests\Fixtures;

use Shoot\Shoot\Context;
use Shoot\Shoot\PresentationModel;
use Shoot\Shoot\PresenterInterface;

final class ProductListPresenter implements PresenterInterface
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
            'products' => [
                new ProductPresentationModel([
                    'product_name' => 'ACME Piano',
                    'on_stock' => true,
                    'stock_quantity' => 6
                ]),
                new ProductPresentationModel([
                    'product_name' => 'ACME Rocket',
                    'on_stock' => false,
                    'stock_quantity' => 0
                ]),
                new ProductPresentationModel([
                    'product_name' => 'ACME Hammer',
                    'on_stock' => true,
                    'stock_quantity' => 17
                ])
            ]
        ]);
    }
}
