<?php
declare(strict_types=1);

namespace Shoot\Shoot;

interface PresenterInterface
{
    /**
     * @param mixed             $context
     * @param PresentationModel $presentationModel
     *
     * @return PresentationModel
     */
    public function present($context, PresentationModel $presentationModel): PresentationModel;
}
