<?php
declare(strict_types=1);

namespace Shoot\Shoot\Utilities;

use Shoot\Shoot\PresentationModel;

/**
 * A convenience trait for Presenters. It checks if a presentation model already holds data. If so, it might not be
 * necessary to perform any action on it.
 */
trait HasDataTrait
{
    /**
     * Returns whether the presentation model already holds data.
     *
     * @param PresentationModel $presentationModel
     *
     * @return bool
     */
    private function hasData(PresentationModel $presentationModel): bool
    {
        foreach ($presentationModel->getVariables() as $value) {
            if (!empty($value)) {
                return true;
            }
        }

        return false;
    }
}
