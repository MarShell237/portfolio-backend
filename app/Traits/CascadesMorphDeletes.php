<?php

namespace App\Traits;

use LogicException;

trait CascadesMorphDeletes
{
    /**
     * Must return the list of morphMany/morphOne relations to delete.
     */
    abstract protected function cascadesMorphDeletes(): array;

    public static function bootCascadesMorphDeletes(): void
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            foreach ($model->cascadesMorphDeletes() as $relation) {
                if (! method_exists($model, $relation)) {
                    throw new LogicException(sprintf(
                        'Relation "%s" is not defined on %s.',
                        $relation, get_class($model)
                    ));
                }

                $model->{$relation}()->get()->each->delete();
            }
        });
    }
}
