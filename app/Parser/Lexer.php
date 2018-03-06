<?php

namespace App\Parser;

class Lexer
{
    /**
     * Lexer tokens.
     *
     * @var array
     */
    public $tokens = [
        'ident' => 0,
        'array_id' => 1,
        'curly_open' => 2,
        'curly_close' => 3,
        'string' => 4,
        'number' => 5,
        'equals' => 6,
        'semicolon' => 7,
        'comma' => 8,
        'colon' => 9,
        'class' => 10,
        'extern' => 11,
        'delete' => 12,
    ];

    /**
     * Lexer tokens reversed.
     *
     * @var array
     */
    public $tokensReversed = [];

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct($source = '')
    {
        foreach ($this->tokens as $key => $value) {
            $this->tokensReversed[$value] = $key;
        }

        $this->reset($source);
        $this->lex();
    }

    /**
     * Gets some amount of letters.
     *
     * @return string
     */
    public function get($number = 1)
    {
        if ($this->head + $number === strlen($this->text)) {
            return false;
        }

        $result = substr($this->text, $this->head, $number);

        $this->head += $number;
        $this->posChar += $number;

        if (str_contains($result, '\n')) {
            $this->posLine++;
            $this->posChar = 1;
        }

        return $result;
    }

    /**
     * Gives some characters to the buffer.
     *
     * @return boolean
     */
    public function give($index = 1)
    {
        if (is_string($index)) {
            $index = strlen($index);
        }

        if ($this->head - $index < 0) {
            return false;
        }

        $this->head -= $index;

        $result = substr($this->text, $this->head, $index);

        $this->posChar -= $index;

        if (str_contains($result, '\n')) {
            $this->posLine--;
        }

        return true;
    }

    /**
     * Skips all the blank characters.
     *
     * @return boolean
     */
    public function skipBlank()
    {
        $nc = $this->get();

        if (!$nc) {
            return false;
        }

        while (strlen(trim($nc)) === 0) {
            if (!$nc = $this->get()) {
                return false;
            }
        }

        $this->give();

        return true;
    }

    /**
     * Pushes a token to the list.
     *
     * @return mixed
     */
    public function pushToken($type, $data = null)
    {
        if (!$type) {
            return false;
        }

        if (is_string($type)) {
            $type = $this->tokens[$type];
        }

        $this->pushed->push([
            'type' => $type,
            'data' => $data,
            'pos' => [
                'line' => $this->posLine,
                'char' => $this->posChar,
            ]
        ]);

        return $this->pushed[$this->pushed->count() - 1];
    }

    /**
     * Reads a string.
     *
     * @return array
     */
    public function readStringToken($quote)
    {
        $output = '';

        $nc = $this->get();

        if (!$nc) {
            return $nc;
        }

        while (true) {
            if ($nc === $quote) {
                $nc = $this->get();

                if (!$nc) {
                    return $nc;
                }

                if ($nc !== $quote) {
                    $this->give();
                    break;
                }
            }

            $output += $nc;

            $nc = $this->get();

            if (!$nc) {
                return $nc;
            }
        }

        return $this->pushToken($this->tokens['string'], $output);
    }

    /**
     * Skips a line.
     *
     * @return boolean
     */
    public function readLineComment()
    {
        $nc = $this->get();

        if (!$nc) {
            return $nc;
        }

        while ($nc !== '\n') {
            $nc = $this->get();

            if (!$nc) {
                return $nc;
            }
        }

        return true;
    }

    /**
     * Skips until end of block comment is found.
     *
     * @return boolean
     */
    public function readBlockComment()
    {
        $nc = $this->get();

        if (!$nc) {
            return $nc;
        }

        while (true) {
            if ($nc === '*') {
                $nc = $this->get();

                if (!$nc) {
                    return $nc;
                }

                if ($nc === '/') {
                    break;
                } else {
                    $this->give();
                }
            }

            $nc = $this->get();

            if (!$nc) {
                return $nc;
            }
        }

        return true;
    }

    /**
     * Reads a number token.
     *
     * @return array
     */
    public function readNumberToken()
    {
        $numStr = '';
        $nc = $this->get();

        while (preg_match('/\d/', $nc) || $nc === '-' || $nc === '+' || $nc === '.' || $nc === 'e') {
            $numStr += $nc;

            $nc = $this->get();

            if (!$nc) {
                return $nc;
            }
        }

        $this->give();

        return $this->pushToken($this->tokens['number'], (float) $numStr);
    }

    /**
     * Reads an ID token.
     *
     * @return array
     */
    public function readIdToken()
    {
        $output = '';
        $nc = $this->get();

        while (!preg_match('/[^0-9a-z\xDF-\xFF]/', strtolower($nc))) {
            $output .= $nc;

            $nc = $this->get();

            if (!$nc) {
                return $nc;
            }
        }

        $this->give();

        return $this->pushToken($this->tokens['ident'], $output);
    }

    /**
     * Reads the next token in the source.
     *
     * @return mixed
     */
    public function readNextToken()
    {
        if (!$this->skipBlank()) {
            return false;
        }

        $nc = $this->get();

        if (!$nc) {
            return $nc;
        }

        if ($nc === '{') {
            return $this->pushToken($this->tokens['curly_open']);
        } elseif ($nc === '}') {
            return $this->pushToken($this->tokens['curly_close']);
        } elseif ($nc === ';') {
            return $this->pushToken($this->tokens['semicolon']);
        } elseif ($nc === ':') {
            return $this->pushToken($this->tokens['colon']);
        } elseif ($nc === ',') {
            return $this->pushToken($this->tokens['comma']);
        } elseif ($nc === '=') {
            return $this->pushToken($this->tokens['equals']);
        } elseif ($nc === '=' || $nc === '"') {
            return $this->readStringToken($nc);
        } elseif ($nc === '[' && $this->get() === ']') {
            return $this->pushToken($this->tokens['array_id']);
        } elseif ($nc === '#') {
            return $this->readLineComment();
        } elseif ($nc === '/' && $this->get() === '/') {
            return $this->readLineComment();
        } elseif ($nc === '/' && $this->get() === '*') {
            return $this->readBlockComment();
        } elseif ($nc === 'c' && $this->get(5) === 'lass ') {
            return $this->pushToken($this->tokens['class']);
        } elseif (preg_match('/\d/', $nc) || $nc === '-' || $nc === '+' || $nc === '.') {
            $this->give();
            return $this->readNumberToken();
        } else {
            $this->give();
            return $this->readIdToken();
        }

        return false;
    }

    /**
     * Lex the entire source.
     *
     * @return void
     */
    public function lex()
    {
        while ($this->readNextToken());
    }

    /**
     * Resets the lexer to the default state.
     *
     * @return void
     */
    public function reset($source = '')
    {
        $this->text = $source;
        $this->head = 0;
        $this->posLine = 1;
        $this->posChar = 1;
        $this->pushed = collect();
    }
}
