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
        $ids = collect();

        foreach ($this->stack->validSyntax->data->sqm->mission->entities as $key => $entity) {
            if ($ids->has($entity->id)) {
                $fail("Entity has a duplicate ID: {$entity->id}");
            }

            $ids->push($entity->id);
        }

        return true;
    }
}
