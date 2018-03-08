<?php

namespace App\Parser;

class Text
{
    /**
     * Blah
     *
     * @var type
     */
    public $tokens = [];

    public $lexer;
    public $head = 0;
    public $root;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct($source)
    {
        $this->lexer = new Lexer($source);

        $this->tokens = $this->lexer->pushed;

        $this->head = 0;
        $this->root = $this->parseClassBody();
    }

    /**
     * Gets the next token I think?
     *
     * @return string?
     */
    public function get()
    {
        if ($this->head >= count($this->tokens)) {
            return false;
        }

        $result = $this->tokens[$this->head];

        $this->head++;

        return $result;
    }

    /**
     * Gives another thingy to the head?
     *
     * @return void
     */
    public function give()
    {
        $this->head--;
    }

    /**
     * Reads the given value?
     *
     * @return mixed?
     */
    public function readValue(&$entry)
    {
        $nt = $this->get();

        if (!$nt) {
            throw new \Exception('Unexpected EOF');
        }

        if ($nt['type'] === $this->lexer->tokens['string']) {
            $entry['subtype'] = Common::$subTypes['string'];
            $entry['value'] = $nt['data'];

            while (($nt = $this->get())['type'] === $this->lexer->tokens['string']) {
                $entry['value'] .= $nt['data'];
            }

            $this->give();
        } elseif ($nt['type'] === $this->lexer->tokens['number']) {
            if ($nt['data'] % 1 === 0) {
                $entry['subtype'] = Common::$subTypes['long'];
            } else {
                $entry['subtype'] = Common::$subTypes['float'];
            }

            $entry['value'] = $nt['data'];
        } elseif ($nt['type'] === $this->lexer->tokens['bool_true']) {
            $entry['subtype'] = Common::$subTypes['bool'];
            $entry['value'] = true;
        } elseif ($nt['type'] === $this->lexer->tokens['bool_false']) {
            $entry['subtype'] = Common::$subTypes['bool'];
            $entry['value'] = false;
        } elseif ($nt['type'] === $this->lexer->tokens['curly_open']) {
            $subent = null;
            $entry['subtype'] = Common::$subTypes['array'];
            $entry['value'] = [];

            while (true) {
                $subent = [];
                $nt = $this->get();

                if (!$nt) {
                    throw new \Exception('Unexpected EOF');
                }

                if ($nt['type'] === $this->lexer->tokens['curly_close']) {
                    break;
                }

                $this->give();
                $this->readValue($subent);
                $entry['value'][] = $subent;

                $nt = $this->get();

                if ($nt['type'] === $this->lexer->tokens['curly_close']) {
                    break;
                }

                if (!$nt || $nt['type'] !== $this->lexer->tokens['comma']) {
                    if (!$nt) {
                        throw new \Exception('Unexpected EOF, expected comma');
                    }

                    throw new \Exception("Expected comma at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                }
            }
        } else {
            throw new \Exception("Unexpected token: {$this->lexer->tokensReversed[$nt['type']]}, at line {$nt['pos']['line']} char {$nt['pos']['char']}");
        }
    }

    /**
     * Parses the class body I guess?
     *
     * @return \App\Parser\Table
     */
    public function parseClassBody()
    {
        $cls = new Table;
        $nt = null;

        while (true) {
            $nt = $this->get();

            if (!$nt) {
                break;
            }

            if ($nt['type'] === $this->lexer->tokens['class']) {
                $entry = ['type' => Common::$types['class']];

                $nt = $this->get();

                if (!$nt || $nt['type'] !== $this->lexer->tokens['ident']) {
                    if (!$nt) {
                        throw new \Exception('Unexpected EOF, expected class name');
                    }

                    throw new \Exception("Expected class name at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                }

                $entry['name'] = $nt['data'];

                $nt = $this->get();

                $clsSuper = null;

                if ($nt['type'] === $this->lexer->tokens['colon']) {
                    $nt = $this->get();

                    if (!$nt || $nt['type'] !== $this->lexer->tokens['ident']) {
                        throw new \Exception("Expected parent class name at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                    }

                    $clsSuper = $nt['data'];
                }

                if (!$nt || $nt['type'] !== $this->lexer->tokens['curly_open']) {
                    throw new \Exception("Expected opening bracket at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                }

                $entry['cls'] = $this->parseClassBody();

                $nt = $this->get();

                if (!$nt || $nt['type'] !== $this->lexer->tokens['curly_close']) {
                    throw new \Exception("Expected closing bracket at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                }

                $cls->addEntry($entry);
            } elseif ($nt['type'] === $this->lexer->tokens['extern']) {
                $entry = ['type' => Common::$types['extern']];

                $nt = $this->get();

                if (!$nt || $nt['type'] !== $this->lexer->tokens['ident']) {
                    throw new \Exception("Expected identifier at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                }

                $entry['name'] = $nt['data'];
                $cls->addEntry($entry);
            } elseif ($nt['type'] === $this->lexer->tokens['delete']) {
                $entry = ['type' => Common::$types['delete']];

                $nt = $this->get();

                if (!$nt || $nt['type'] !== $this->lexer->tokens['ident']) {
                    throw new \Exception("Expected identifier at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                }

                $entry['name'] = $nt['data'];

                $cls->addEntry($entry);
            } elseif ($nt['type'] === $this->lexer->tokens['ident']) {
                $entry = [];

                $entry['name'] = $nt['data'];

                $nt = $this->get();

                if (!$nt) {
                    throw new \Exception('Unexpected EOF');
                }

                $entry['type'] = Common::$types['value'];

                if ($nt['type'] === $this->lexer->tokens['array_id']) {
                    $entry['type'] = Common::$types['array'];

                    $nt = $this->get();

                    if (!$nt) {
                        throw new \Exception('Unexpected EOF');
                    }
                }

                if ($nt['type'] !== $this->lexer->tokens['equals']) {
                    throw new \Exception("Expected equals sign at line {$nt['pos']['line']} char {$nt['pos']['char']}");
                }

                $this->readValue($entry);

                $cls->addEntry($entry);
            } elseif ($nt['type'] === $this->lexer->tokens['curly_close']) {
                $this->give();
                break;
            }

            $nt = $this->get();

            if (!$nt || $nt['type'] !== $this->lexer->tokens['semicolon']) {
                if (!$nt) {
                    throw new \Exception('Expected EOF, expected semicolon');
                }

                throw new \Exception("Expected semicolon at line {$nt['pos']['line']} char {$nt['pos']['char']}");
            }
        }

        return $cls;
    }
}
