<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;


 class TraineeRoutes{
    public function register(){
        add_action('rest_api_init', array($this, 'register_trainee_routes'));
    }
    public function register_trainee_routes(){
        register_rest_route(
            'easymanage/v3',
            '/assignedtasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_tasks'),
                'permission_callback' => function () {
                    return current_user_can('Trainee');
                }
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