<?php

class Bootstrap {
    public static function error($message) {
        return empty($message) ? '' : 'has-error';
    }
}