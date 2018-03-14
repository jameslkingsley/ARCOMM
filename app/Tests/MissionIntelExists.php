<?php

namespace App\Tests;

class MissionIntelExists extends MissionTest
{
    /**
     * Determines if the test passes.
     *
     * @return boolean
     */
    public function passes($fail, $data)
    {
        $hasIntel = property_exists($this->stack->validSyntax->data->sqm, 'mission')
            && property_exists($this->stack->validSyntax->data->sqm->mission, 'intel')
            && property_exists($this->stack->validSyntax->data->sqm->mission->intel, 'year')
            && property_exists($this->stack->validSyntax->data->sqm->mission->intel, 'month')
            && property_exists($this->stack->validSyntax->data->sqm->mission->intel, 'day')
            && property_exists($this->stack->validSyntax->data->sqm->mission->intel, 'hour')
            && property_exists($this->stack->validSyntax->data->sqm->mission->intel, 'minute');

        if (!$hasIntel) {
            return $fail('mission.sqm is missing the following properties in Mission >> Intel: year, month, day, hour, minute');
        }

        return true;
    }
}
