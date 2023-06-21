<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;
 use WP_Error;


 class PmRoutes{
    public function register(){
        add_action('rest_api_init', array($this, 'register_pm_routes'));
    }
    public function register_pm_routes(){
        register_rest_route(
            'easymanage/v2',
            '/trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainers'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainer'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_trainer'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/trainers/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_trainer'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/trainers/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_trainer'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/trainees/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainees'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/trainees/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainee'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

    }

    public function get_trainers(){
        global $wpdb;
        $query = "
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
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
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
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

    public function update_trainer($request) {
    global $wpdb;

    $user_id = $request->get_param('id');
    $trainer = get_user_by('ID', $user_id);

    // Retrieve existing user data
    if (!$trainer) {
        return new \WP_Error('user-not-found', 'User not found', array('status' => 404));
    }

    // Update user data
    $updated_user = array(
        'user_email' => $request->get_param('email'),
        'user_pass' => $request->get_param('password')
    );

    $role = $request->get_param('role');
    $lastname = $request->get_param('lastname');

    $req = $wpdb->update(
        $wpdb->users,
        $updated_user,
        array('ID' => $user_id)
    );

    if ($req !== false) {
        update_user_meta($user_id, 'last_name', $lastname);
        update_user_meta($user_id, 'role', $role);

        $res = "Trainer Updated";
        return rest_ensure_response($res);
    } else {
        $wpdb_error = $wpdb->last_error;
        $error_message = 'Cannot update Trainer: ' . $wpdb_error;
        error_log($error_message); // Log the error message for debugging purposes

        return new \WP_Error('cant-update', $error_message, array('status' => 500));
    }
}




   public function create_trainer($request) {
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

    if ($req) {
        $user_id = $wpdb->insert_id;

        update_user_meta($user_id, 'last_name', $lastname);
        update_user_meta($user_id, 'role', $role);

        $res = "Trainer Created";
        return rest_ensure_response($res);
    } else {
        $wpdb_error = $wpdb->last_error;
        $error_message = 'Cannot create Trainer: ' . $wpdb_error;
        error_log($error_message); // Log the error message for debugging purposes

        return new \WP_Error('cant-create', $error_message, array('status' => 500));
    }
}


    public function get_trainees(){
        global $wpdb;
        $query = "
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
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
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' AND users.ID = {$id}
        ";
        $trainee = $wpdb->get_results($query);
        return $trainee;
    }
 }