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

        register_rest_route(
            'easymanage/v2',
            '/non-deleted-trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'view_non_deleted_trainers'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

    }

    public function get_trainers(){
        $args = array(
        'role'    => 'trainer', // Specify the desired role
        'orderby' => 'registered',
        'order'   => 'DESC',
    );
    
    $trainers = get_users($args);
    
    $trainer_list = array();
    foreach ($trainers as $trainer) {
        $trainer_data = array(
            'id'         => $trainer->ID,
            'username'   => $trainer->user_login,
            'email'      => $trainer->user_email,
            'first_name' => $trainer->first_name,
            'last_name'  => $trainer->last_name,
            'created_by' => get_user_meta($trainer->ID, 'created_by', true),
        );
        
        $trainer_list[] = $trainer_data;
    }
        if($trainer_list){
            return $trainer_list;
        } else {
            return new \WP_Error('cant-get', 'Cant get trainer', array('status' => 500));
        }
    }



    public function get_trainer($request) {
        $trainer_id = $request->get_param('id');
    $trainer = get_user_by('ID', $trainer_id);

    if ($trainer) {
        $trainer_data = array(
            'user_login' => $trainer->user_login,
            'user_email' => $trainer->user_email,
            'first_name' => $trainer->first_name,
            'last_name' => $trainer->last_name,
            'created_by' => get_user_meta($trainer_id, 'created_by', true),
            'role' => $trainer->roles[0], 
        );

        return rest_ensure_response($trainer_data);
    } else {
        return new \WP_Error('trainer-not-found', 'Trainer not found', array('status' => 404));
    }
}

    public function delete_trainer($request) {
        $user_id = $request['id'];
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return new \WP_Error('user_not_found', 'Trainer not found', ['status' => 404]);
        }

        // Update user meta to mark the trainer as deleted
        $is_deleted = update_user_meta($user_id, 'is_deleted', true);

        if (!$is_deleted) {
            return new \WP_Error('delete_failed', 'Trainer deletion failed', ['status' => 500]);
        }

        // Check if the user is an admin
        $user_roles = $user->roles;
        if (in_array('administrator', $user_roles)) {
            return new \WP_Error('delete_admin_not_allowed', 'Deleting admin user is not allowed', ['status' => 403]);
        }

        if (in_array('project_manager', $user_roles)) {
            return new \WP_Error('delete_project_manager_not_allowed', 'Deleting project manager user is not allowed', ['status' => 403]);
        }

        return 'Trainer soft deleted successfully';
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
    $trainer_logged_in = wp_get_current_user();

    $params = $request->get_params();
    $user_login = $params['firstname'];
    $user_email = $params['email'];
    $user_pass = $params['password'];

    $role = $params['role'];
    $lastname = $params['lastname'];
    $created_by = $trainer_logged_in->first_name . ' ' . $trainer_logged_in->last_name;

    $user_id = wp_create_user($user_login, $user_pass, $user_email);

    if (!is_wp_error($user_id)) {
        $user = get_user_by('id', $user_id);

        $user->set_role($role);
        wp_update_user($user);

        update_user_meta($user_id, 'last_name', $lastname);
        update_user_meta($user_id, 'created_by', $created_by);

        $res = "Trainer Created";
        return rest_ensure_response($res);
    } else {
        $wpdb_error = $wpdb->last_error;
        $error_message = 'Cannot create Trainer: ' . $wpdb_error;
        error_log($error_message); 

        return new \WP_Error('cant-create', $error_message, array('status' => 500));
    }
}


    public function get_trainees(){
        $args = array(
        'role'    => 'trainee', // Specify the desired role
        'orderby' => 'registered',
        'order'   => 'DESC',
    );
    
    $trainees = get_users($args);
    
    $trainee_list = array();
    foreach ($trainees as $trainee) {
        $trainee_data = array(
            'id'         => $trainee->ID,
            'username'   => $trainee->user_login,
            'email'      => $trainee->user_email,
            'first_name' => $trainee->first_name,
            'last_name'  => $trainee->last_name,
            'cohort'     => get_user_meta($trainee->ID, 'cohort', true),
            'created_by' => get_user_meta($trainee->ID, 'created_by', true),
        );
        
        $trainee_list[] = $trainee_data;
    }
        if($trainee_list){
            return $trainee_list;
        } else {
            return new \WP_Error('cant-get', 'Cant get trainee', array('status' => 500));
        }
    }
    

    public function get_trainee($request) {
        $trainee_id = $request->get_param('id');
    $trainee = get_user_by('ID', $trainee_id);

    if ($trainee) {
        $trainee_data = array(
            'user_login' => $trainee->user_login,
            'user_email' => $trainee->user_email,
            'first_name' => $trainee->first_name,
            'last_name' => $trainee->last_name,
            'cohort' => get_user_meta($trainee_id, 'cohort', true),
            'created_by' => get_user_meta($trainee_id, 'created_by', true),
            'role' => $trainee->roles[0], // Assuming the trainee has only one role
        );

        return rest_ensure_response($trainee_data);
    } else {
        return new \WP_Error('trainee-not-found', 'Trainee not found', array('status' => 404));
    }
}

    public function view_non_deleted_trainers() {
    $non_deleted_trainers = get_users(array(
        'role' => 'trainer',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key'     => 'is_active',
                'value'   => true,
            ),
            array(
                'key'     => 'is_deleted',
                'value'   => false,
            ),
        ),
    ));

    if (empty($non_deleted_trainers)) {
        return new \WP_Error('no_non_deleted_trainers', 'No non-deleted trainers found', ['status' => 404]);
    }

    if(is_wp_error($non_deleted_trainers)){
        return new \WP_Error('cant-get', 'Cant get non-deleted trainers', array('status' => 500));
    }
    return $non_deleted_trainers;
}

 }