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

// require_once plugin_dir_path(__FILE__) . 'inc/Pages/AdminRoutes.php';
use Inc\Pages\AdminRoutes;
use Inc\Pages\PmRoutes;
use Inc\Pages\TrainerRoutes;
use Inc\Pages\TraineeRoutes;

function easy_manage_register_routes() {
    $admin_routes = new AdminRoutes();
    $admin_routes->register_admin_routes();
    
    $pm_routes = new PmRoutes();
    $pm_routes->register_pm_routes();

    $trainer_routes = new TrainerRoutes();
    $trainer_routes->register_trainer_routes();

    $trainee_routes = new TraineeRoutes();
    $trainee_routes->register_trainee_routes();

}
add_action('rest_api_init', 'easy_manage_register_routes');