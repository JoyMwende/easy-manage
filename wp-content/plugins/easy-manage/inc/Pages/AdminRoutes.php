<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;


 class AdminRoutes{

    public function register(){
        add_action('rest_api_init', array($this, 'register_admin_routes'));
    }
    public function register_admin_routes(){
        register_rest_route(
            'easymanage/v1',
            '/users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_users'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/users/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_user'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/users/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_user'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );



        register_rest_route(
            'easymanage/v1',
            '/project-managers/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_project_manager'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainees/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainees'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/project-managers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_project_managers'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainers'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );
    }

    public function get_users(){
        global $wpdb;
        $users = $wpdb->get_results("
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role'
        ");

        if($users){
            return $users;
        } else {
            return new \WP_Error('cant-get', 'Cant Get Users', array('status' => 500));
        }
    }

    public function get_user($request){
        global $wpdb;
        $id = $request['id'];
        $user = $wpdb->get_results("
        SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE users.ID = $id
        ");

        if($user){
            return $user;
        } else {
            return new \WP_Error('cant-get', 'Cant Get User', array('status' => 500));
        }
    }

    public function delete_user($request){
        global $wpdb;
        $id = $request['id'];
        $result = $wpdb->delete(
            $wpdb->users,
            array('ID' => $id)
        );

        if($result){
            return 'User Deleted';
        } else {
            return new \WP_Error('cant-delete', 'Cant Delete User', array('status' => 500));
        }
    }

    


    public function create_project_manager($request){
        global $wpdb;

        $params = $request->get_params();
        $user = array(
            'user_login' => $params['firstname'],
            'user_email' => $params['email'],
            'user_pass' => $params['password']
        );

        $role = $params['role'];
        $lastname = $params['lastname'];

        $req = $wpdb->insert(
            $wpdb->users,
            $user
        );

        if($req){
            $user_id = $wpdb->insert_id;
            update_user_meta($user_id, 'last_name', $lastname);
            update_user_meta($user_id, 'role', $role);
            $res = "Project Manager Created";
            return rest_ensure_response($res);
        } else {
            return new \WP_Error('cant-create', 'Cant Create Project Manager', array('status' => 500));
        }

    }
    public function get_trainees(){
        global $wpdb;
        $trainees = $wpdb->get_results("
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' 
        ");
        if($trainees){
            return $trainees;
        } else {
            return new \WP_Error('cant-get', 'Cant Get Trainees', array('status' => 500));
        }
    }

    public function get_project_managers(){
        global $wpdb;
        $project_managers = $wpdb->get_results("
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE meta2.meta_value = 'Project Manager' 
        ");

        if($project_managers){
            return $project_managers;
        } else {
            return new \WP_Error('cant-get', 'Cant Get Project Managers', array('status' => 500));
        }
    }

    public function get_trainers(){
        global $wpdb;
        $trainers = $wpdb->get_results("
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE meta2.meta_value = 'Trainer' 
        ");

        if($trainers){
            return $trainers;
        } else {
            return new \WP_Error('cant-get', 'Cant Get Trainers', array('status' => 500));
        }
    }
 }