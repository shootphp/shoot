<?php
declare(strict_types=1);

namespace Shoot\Shoot;

interface PresenterInterface
{
    /**
     * @param ContextInterface  $context
     * @param PresentationModel $presentationModel
     *
     * @return PresentationModel
     */
    public function present(ContextInterface $context, PresentationModel $presentationModel): PresentationModel;
}
