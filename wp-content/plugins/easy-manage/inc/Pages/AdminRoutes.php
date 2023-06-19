<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;

 class AdminRoutes{
    public function register_admin_routes(){
        register_rest_route(
            'easymanage/v1',
            '/users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_users')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/users/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_user')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/users/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_user')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/users/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_user')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/users/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_user')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/project-managers/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_project_manager')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainees/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainees')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/project-managers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_project_managers')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainers')
            )
        );
    }

    public function get_users(){
        global $wpdb;
        $users = $wpdb->get_results("
        SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role'
    ");
        return $users;
    }

    public function get_user($request){
        global $wpdb;
        $id = $request['id'];
        $user = $wpdb->get_results("
        SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE users.ID = $id
        ");
        return $user;
    }

    public function delete_user($request){
        global $wpdb;
        $id = $request['id'];
        $wpdb->delete(
            $wpdb->users,
            array('ID' => $id)
        );
        return 'User Deleted';
    }

    public function update_user($request){
        global $wpdb;
        $id = $request['id'];
        $params = $request->get_params();
        $user = array(
            'user_login' => $params['firstname'],
            'user_email' => $params['email'],
            'last_name' => $params['lastname'],
            'role' => $params['role']
        );
        $wpdb->update(
            $wpdb->users,
            $user,
            array('ID' => $id)
        );
        return 'User Updated';
    }

    public function create_user($request){
        global $wpdb;
        $params = $request->get_params();
        $user = array(
            'user_login' => $params['firstname'],
            'user_email' => $params['email'],
            'last_name' => $params['lastname'],
            'role' => $params['role']
        );
        $wpdb->insert(
            $wpdb->users,
            $user
        );
        return 'User Created';
    }

    public function create_project_manager($request){
        global $wpdb;
        $params = $request->get_params();
        $user = array(
            'user_login' => $params['firstname'],
            'user_email' => $params['email'],
            'last_name' => $params['lastname'],
            'role' => $params['role']
        );
        $wpdb->insert(
            $wpdb->users,
            $user
        );
        return 'Project Manager Created';
    }

    public function get_trainees(){
        global $wpdb;
        $trainees = $wpdb->get_results("
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' 
        ");
        return $trainees;
    }

    public function get_project_managers(){
        global $wpdb;
        $project_managers = $wpdb->get_results("
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE meta2.meta_value = 'Project Manager' 
        ");
        return $project_managers;
    }

    public function get_trainers(){
        global $wpdb;
        $trainers = $wpdb->get_results("
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE meta2.meta_value = 'Trainer' 
        ");
        return $trainers;
    }
 }