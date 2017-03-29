<?php

namespace App\Helpers;

use File;

class ArmaScript
{
    /**
     * Checks the given file for SQF syntax errors.
     * Returns any error messages, or empty string.
     *
     * @return string
     */
    public static function check($source)
    {
        if (!is_dir($source)) {
            return shell_exec('python '.resource_path('utils/sqf_validator.py').' '.$source);
        } else {
            $files = File::allFiles($source);

            foreach ($files as $file) {
                $file = (string)$file;

                if (!ends_with(strtolower($file), 'sqf')) {
                    continue;
                }

                $display_name = substr($file, strlen($source));
                $output = "<b>{$display_name}</b><br />";
                $result = shell_exec('python '.resource_path('utils/sqf_validator.py').' '.$file);
                $lines = explode('\n', $result);
                $lines = implode('<br />', $lines);
                $output = $output . $lines;

                if (strlen(trim($lines)) != 0) {
                    return $output;
                }
            }
        }
    }
}
