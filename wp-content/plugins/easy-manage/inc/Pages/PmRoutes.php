<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;

 class PmRoutes{
    public function register_pm_routes(){
        register_rest_route(
            'easymanage/v1',
            '/trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainers')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainer')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_trainer')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_trainer')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_trainer')
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
            '/trainees/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainee')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_trainer')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_trainer')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainers/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_trainer')
            )
        );
    }

    public function get_trainers(){
        global $wpdb;
        $query = "
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE meta2.meta_value = 'Trainer' 
    ";
        $trainers = $wpdb->get_results($query);
        return $trainers;
    }

    public function get_trainer($request){
        global $wpdb;
        $id = $request['id'];
        $query = "
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE meta2.meta_value = 'Trainer' AND users.ID = {$id}
    ";
        $trainer = $wpdb->get_results($query);
        return $trainer;
    }

    public function delete_trainer($request){
        global $wpdb;
        $id = $request['id'];
        $wpdb->delete($wpdb->users, array('ID' => $id));
        return 'Trainer Deleted';
    }

    public function update_trainer($request){
        global $wpdb;
        $id = $request['id'];
        $params = $request->get_params();
        $trainer = array(
            'user_login' => $params['firstname'],
            'user_email' => $params['email'],
            'last_name' => $params['lastname'],
            'role' => $params['role']
        );
        $wpdb->update($wpdb->users, $trainer, array('ID' => $id));
        return 'Trainer Updated';
    }

    public function create_trainer($request){
        global $wpdb;
        $table = $wpdb->prefix . 'users';
        $params = $request->get_params();
        $trainer = array(
            'user_login' => $params['firstname'],
            'user_email' => $params['email'],
            'last_name' => $params['lastname'],
            'role' => $params['role']
        );
        $wpdb->insert($table, $trainer);
        return 'Trainer Created';
    }

    public function get_trainees(){
        global $wpdb;
        $query = "
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' 
        ";
        $trainees = $wpdb->get_results($query);
        return $trainees;
    }

    public function get_trainee($request){
        global $wpdb;
        $id = $request['id'];
        $query = "
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' AND users.ID = {$id}
        ";
        $trainee = $wpdb->get_results($query);
        return $trainee;
    }
 }