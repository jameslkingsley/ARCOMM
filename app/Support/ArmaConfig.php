<?php

namespace App\Support;

use App\Parser\Text;
use App\Parser\Lexer;
use App\Parser\Common;

class ArmaConfig
{
    /**
     * Original config contents.
     *
     * @var string
     */
    protected $contents;

    /**
     * Parsed config object.
     *
     * @var array
     */
    protected $parsed;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct(string $contents)
    {
        $this->contents = $this->clean($contents);

        $this->parsed = $this->compile(
            (new Text($this->contents))->root
        );
    }

    /**
     * Cleans the source content of comments.
     *
     * @return string
     */
    public function clean($source)
    {
        // Remove single line comments
        $source = preg_replace('#^\s*//.+$#m', '', $source);

        // Remove multi line comments
        $source = preg_replace('!/\*.*?\*/!s', '', $source);

        return $source;
    }

    /**
     * Parses the given config into PHP.
     *
     * @return array
     */
    public static function parse($source)
    {
        return (new static($source))->toArray();
    }

    /**
     * Compiles the lexer data into a PHP array.
     *
     * @return array
     */
    public function compile($entry)
    {
        if (array_key_exists('subtype', $entry)) {
            if ($entry['subtype'] === Common::$subTypes['array']) {
                $array = [];

                foreach ($entry['value'] as $item) {
                    $array[] = $this->compile($item);
                }

                return $array;
            }

            return $entry['value'];
        } else {
            $result = [];

            foreach ($entry->entries as $object) {
                if ($object['type'] === Common::$types['class']) {
                    $result[$object['name']] = $this->compile($object['cls']);
                } elseif ($object['type'] === Common::$types['array']) {
                    $array = [];

                    foreach ($object['value'] as $item) {
                        $array[] = $this->compile($item);
                    }

                    $result[$object['name']] = $array;
                } else {
                    $result[$object['name']] = $object['value'];
                }
            }

            return $result;
        }
    }

    /**
     * Gets the parsed config as JSON.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Gets the parsed config as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this->parsed;
    }

    /**
     * Proxy for getting value from parsed config.
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->parsed->{$name};
    }
}
