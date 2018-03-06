<?php

namespace App\Parser;

class Table
{
    /**
     * Object entries.
     *
     * @var array
     */
    public $entries = [];

    /**
     * Adds a new entry.
     *
     * @return array
     */
    public function addEntry($type)
    {
        $entry = ['type' => $type];

        $this->entries[] = $entry;

        return $entry;
    }
}
