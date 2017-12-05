<?php
declare(strict_types=1);

namespace Shoot\Shoot;

interface PresenterInterface
{
    /**
     * @param Context           $context
     * @param PresentationModel $presentationModel
     *
     * @return PresentationModel
     */
    public function present(Context $context, PresentationModel $presentationModel): PresentationModel;
}
