<?php

namespace App\Support;

class ArmaConfigParser
{
    /**
     * Lexer terminals.
     *
     * @var array
     */
    protected $terminals = [
        '/^(class\s)/' => 'T_CLASS',
        '/^(\s+)/' => 'T_WHITESPACE',
        '/^([-0-9\+\.]+e*[-0-9\+\.]*)/' => 'T_NUMBER',
        '/^([A-Za-z_]+[0-9_]*[A-Za-z_]*)/' => 'T_PROP',
        '/^({)/' => 'T_BLOCKSTART',
        '/^(=)/' => 'T_ASSIGNMENT',
        '/^(;)/' => 'T_EOL',
        '/^(})/' => 'T_BLOCKEND',
        '/^("[\s\S]*?")/' => 'T_STRING',
        '/^(\\n)/' => 'T_NEWLINE',
        '/^(\[\s*\]\s*=)/' => 'T_ARRAY_START',
        '/^(,)/' => 'T_ARRAY_SEP',
        '/^({[\s\S]*})/' => 'T_ARRAY',
        '/^(#include[\s\S]+)/' => 'T_INCLUDE'
    ];

    /**
     * Source config contents.
     *
     * @var string
     */
    protected $source;

    /**
     * Lines of the source config.
     *
     * @var array
     */
    protected $lines;

    /**
     * Constructor method.
     *
     * @return void
     */
    public function __construct(string $source)
    {
        $this->source = $source;

        $this->prepare();
    }

    /**
     * Prepares the source line strings.
     *
     * @return void
     */
    public function prepare()
    {
        // Remove single line comments
        $source = preg_replace('#^\s*//.+$#m', '', $this->source);

        // Remove multi line comments
        $source = preg_replace('!/\*.*?\*/!s', '', $source);

        $this->lines = explode(PHP_EOL, $source);
    }

    /**
     * Runs through the lines.
     *
     * @return void
     */
    public function run()
    {
        $tokens = [];
        $emptyLines = 0;

        foreach ($this->lines as $number => $line) {
            $offset = 0;

            if (strlen(trim($line)) === 0) {
                $emptyLines++;
            } else {
                while ($offset < strlen($line)) {
                    $result = $this->match($line, $number, $offset);

                    if (!$result) {
                        $realLine = $number + $emptyLines;
                        throw new \Exception("Unable to parse line {$realLine}");
                    }

                    $tokens[] = $result;
                    $offset += strlen($result['match']);
                }
            }
        }

        return $this->compile($this->clean($tokens));
    }

    /**
     * Matches the given line with one of the terminals.
     *
     * @return array|null
     */
    protected function match($line, $number, $offset)
    {
        $string = substr($line, $offset);

        foreach ($this->terminals as $pattern => $name) {
            if (preg_match($pattern, $string, $matches)) {
                return [
                    'token' => $name,
                    'line' => $number + 1,
                    'match' => $matches[1],
                ];
            }
        }

        return null;
    }

    /**
     * Cleans the array of incorrect types.
     *
     * @return array
     */
    protected function cleanArray($items)
    {
        $result = [];

        foreach ($items as $i) {
            if (is_numeric($i)) {
                $result[] = (float)$i;
                continue;
            }

            if (is_string($i)) {
                if ($i == 'true') {
                    $result[] = true;
                    continue;
                }

                if ($i == 'false') {
                    $result[] = false;
                    continue;
                }

                $result[] = str_replace('"', '', $i);
                continue;
            }

            $result[] = $i;
        }

        return $result;
    }

    /**
     * Compiles the tokens into a PHP array.
     *
     * @return array
     */
    protected function compile($tokens)
    {
        $object = [];
        $isClass = false;
        $hasAssignment = false;
        $seeker = '';

        foreach ($tokens as $t) {
            $token = $t['token'];
            $match = $t['match'];
            $value = array_get($t, 'value', $match);

            if ($token === 'T_BLOCKEND') {
                $seeker = explode('.', $seeker);
                array_pop($seeker);
                $seeker = implode('.', $seeker);
                continue;
            }

            if ($token === 'T_CLASSNAME') {
                $seeker = ($seeker == '') ? $match : $seeker . '.' . $match;
                array_set($object, $seeker, []);
                continue;
            }

            if ($token === 'T_ARRAY') {
                $seeker = ($seeker == '') ? $match : $seeker . '.' . $match;
                array_set($object, $seeker, $this->cleanArray($value));
                $hasAssignment = false;
                $seeker = explode('.', $seeker);
                array_pop($seeker);
                $seeker = implode('.', $seeker);
                continue;
            }

            if ($hasAssignment && in_array($token, ['T_STRING', 'T_PROP', 'T_NUMBER'])) {
                array_set($object, $seeker, $value);
                $hasAssignment = false;
                $seeker = explode('.', $seeker);
                array_pop($seeker);
                $seeker = implode('.', $seeker);
                continue;
            }

            if ($token === 'T_PROP' && !$hasAssignment) {
                $seeker = ($seeker == '') ? $match : $seeker . '.' . $match;
                array_set($object, $seeker, []);
                $hasAssignment = true;
                continue;
            }
        }

        return $object;
    }

    /**
     * Cleans the tokens of useless data.
     *
     * @return array
     */
    protected function clean($tokens)
    {
        $cleaned = [];
        $arrayStart = false;
        $arrayTemp = [];
        $arrayTempName = '';
        $arrayTempStartFilling = false;
        $prevMatch = '';
        $prevIndex = 0;
        $nextIsClassname = false;
        $nestedArray = [];
        $isNested = false;

        foreach ($tokens as $t) {
            if ($arrayStart) {
                if ($t['token'] === 'T_ARRAY_SEP') {
                    continue;
                }

                if ($t['token'] === 'T_BLOCKSTART' && !$arrayTempStartFilling) {
                    $arrayTempStartFilling = true;
                    continue;
                }

                if ($t['token'] === 'T_BLOCKSTART' && $arrayTempStartFilling) {
                    $isNested = true;
                    continue;
                }

                if ($t['token'] === 'T_BLOCKEND') {
                    if ($isNested) {
                        array_push($arrayTemp, $this->cleanArray($nestedArray));
                        $nestedArray = [];
                        $isNested = false;
                        continue;
                    } else {
                        $cleaned[$prevIndex]['token'] = 'T_ARRAY';
                        $cleaned[$prevIndex]['value'] = $arrayTemp;
                        $cleaned[$prevIndex]['match'] = $arrayTempName;
                        $arrayTempStartFilling = false;
                        $arrayStart = false;
                        $arrayTempName = '';
                        $arrayTemp = [];
                        continue;
                    }
                }

                if ($arrayTempStartFilling && $t['token'] !== 'T_WHITESPACE') {
                    if ($isNested) {
                        array_push($nestedArray, $t['match']);
                        continue;
                    } else {
                        array_push($arrayTemp, $t['match']);
                        continue;
                    }
                }

                continue;
            }

            if ($nextIsClassname && $t['token'] === 'T_PROP') {
                $t['token'] = 'T_CLASSNAME';
                $cleaned[] = $t;
                $nextIsClassname = false;
                continue;
            }

            if ($t['token'] === 'T_CLASS') {
                $nextIsClassname = true;
                $cleaned[] = $t;
                continue;
            }

            if ($t['token'] === 'T_NUMBER') {
                $t['value'] = (float) str_replace([';', ',', '}', '{'], '', $t['match']);
                $cleaned[] = $t;
                continue;
            }

            if ($t['token'] === 'T_STRING') {
                $t['value'] = str_replace('"', '', $t['match']);
                $cleaned[] = $t;
                continue;
            }

            if ($t['token'] === 'T_ARRAY_START') {
                $arrayStart = true;
                $arrayTempName = $prevMatch;
                continue;
            }

            $cleaned[] = $t;
            $prevIndex = count($cleaned) - 1;
            $prevMatch = $t['match'];
        }

        return $cleaned;
    }
}
