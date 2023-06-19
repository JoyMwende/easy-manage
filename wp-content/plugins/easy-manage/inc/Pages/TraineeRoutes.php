<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;

 class TraineeRoutes{
    public function register_trainee_routes(){
        register_rest_route(
            'easymanage/v1',
            '/tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_tasks')
            )
        );
    }

    public function get_tasks(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';
        $tasks = $wpdb->get_results("SELECT * FROM $table_name");
        return $tasks;
    }
 }