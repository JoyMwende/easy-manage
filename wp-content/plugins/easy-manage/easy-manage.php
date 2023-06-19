<?php
/**
 * @package Easy Manage Plugin
 */

/*
    Plugin Name: Easy Manage Plugin
    Plugin URI: http://.........
    Description: This is a plugin for trainee management system
    Version: 1.0.0
    Author: Joy Mwende
    Author URI: http://eazzymanage...............
    Licence: GPLv2 or later
    Text Domain: easy-manage-plugin
*/

// security check
defined('ABSPATH') or die('Hey you hacker!. Got you!!');


// Checking if composer exists
if(file_exists(dirname(__FILE__).'/vendor/autoload.php')){
    require_once(dirname(__FILE__).'/vendor/autoload.php');
}
function activate_easymanageplugin(){
    Inc\Base\Activate::activate();
}

function deactivate_easymanageplugin(){
    Inc\Base\Deactivate::deactivate();
}

if(class_exists('Inc\\Init')){
    Inc\Init::register_services();
}