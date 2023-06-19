<?php

/**
 * @package Easy Manage Plugin
 */

namespace Inc\Base;

 class Deactivate{
    static function deactivate(){
        flush_rewrite_rules();
    }
 }