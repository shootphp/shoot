<?php
declare(strict_types=1);

namespace Shoot\Shoot;

/**
 * A convenience trait for Presenters. It checks if a presentation model already holds data. If so, it might not be
 * necessary to perform any action on it.
 */
trait HasDataTrait
{
    /**
     * Determine whether the presentation model already holds data.
     *
     * @param PresentationModel $presentationModel The presentation model for which to determine whether it holds data.
     *
     * @return bool Whether the presentation model already holds data.
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
