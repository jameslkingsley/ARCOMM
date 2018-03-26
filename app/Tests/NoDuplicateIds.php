<?php

namespace App\Tests;

class NoDuplicateIds extends MissionTest
{
    /**
     * Determines if the test passes.
     *
     * @return boolean
     */
    public function passes($fail, $data)
    {
        $ids = collect($this->stack->validSyntax->data->sqm->mission->entities)
            ->map(function ($entity) {
                if (is_object($entity)) {
                    return $entity->id;
                }

                return null;
            })
            ->reject(function ($entity) {
                return is_null($entity);
            });

        if ($ids->unique()->count() !== $ids->count()) {
            $fail('Duplicate IDs found in mission.sqm');
        }

        return true;
    }
}
