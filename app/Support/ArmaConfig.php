<?php

namespace App\Support;

use App\Parser\Text;

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
        $this->contents = $contents;

        $this->parse();
    }

    /**
     * Parses the config into PHP.
     *
     * @return void
     */
    public function parse()
    {
        // dd(new Text($this->contents));

        // $parser = new ArmaConfigParser($this->contents);

        $this->parsed = new Text($this->contents);
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
