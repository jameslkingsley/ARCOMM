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
    public function addEntry($data)
    {
        $this->entries[] = $data;
        return $data;
    }
}
