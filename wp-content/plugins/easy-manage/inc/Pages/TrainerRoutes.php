<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;


 class TrainerRoutes{
    public function register(){
        add_action('rest_api_init', array($this, 'register_trainer_routes'));
    }

    public function register_trainer_routes(){
        register_rest_route(
            'easymanage/v4',
            '/trainees/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainees'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_trainee'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_trainee'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_trainee'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_trainee'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/tasks/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_all_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );
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
            return new \WP_Error('cant-get', 'Cant get trainee', array('status' => 500));
        }
    }
    
    public function get_trainee($data){
        global $wpdb;
        $id = $data['id'];
        $trainee = $wpdb->get_results("
            SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' AND users.ID = {$id}
        ");

        if($trainee){
            return $trainee;
        } else {
            return new \WP_Error('cant-get', 'Cant get trainee', array('status' => 500));
        }
    }

    public function delete_trainee($data){
        global $wpdb;
        $id = $data['id'];
        $deleteTrainee = $wpdb->delete("{$wpdb->users}", array('ID' => $id));

        if($deleteTrainee){
            return 'Trainee Deleted';
        } else {
            return new \WP_Error('cant-delete', 'Cant delete trainee', array('status' => 500));
        }
    }

    public function update_trainee($data){
        global $wpdb;
        $id = $data['id'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $role = $data['role'];
        $cohort = $data['cohort'];
        $updated_trainee_data = $wpdb->update("{$wpdb->users}", array('user_login' => $firstname, 'user_email' => $email), array('ID' => $id));
        $updated_trainee_lname = $wpdb->update("{$wpdb->usermeta}", array('meta_value' => $lastname), array('user_id' => $id, 'meta_key' => 'last_name'));
        $updated_trainee_role = $wpdb->update("{$wpdb->usermeta}", array('meta_value' => $role), array('user_id' => $id, 'meta_key' => 'role'));
        $updated_trainee_cohort = $wpdb->update("{$wpdb->usermeta}", array('meta_value' => $cohort), array('user_id' => $id, 'meta_key' => 'cohort'));

        if($updated_trainee_data && $updated_trainee_lname && $updated_trainee_role && $updated_trainee_cohort){
            return 'Trainee Updated';
        } else {
            return new \WP_Error('cant-update', 'Cant update trainee', array('status' => 500));
        }
    }

    public function create_trainee($request) {
    global $wpdb;
    $trainer_logged_in = wp_get_current_user();

    $params = $request->get_params();
    $user = array(
        'user_login' => $params['firstname'],
        'user_email' => $params['email'],
        'user_pass' => $params['password']
    );

    $cohort = $params['cohort'];
    $role = $params['role'];
    $lastname = $params['lastname'];
    $created_by = $trainer_logged_in->first_name . ' ' . $trainer_logged_in->last_name;

    $req = $wpdb->insert(
        $wpdb->users,
        $user
    );

    if ($req) {
        $user_id = $wpdb->insert_id;

        update_user_meta($user_id, 'last_name', $lastname);
        update_user_meta($user_id, 'role', $role);
        update_user_meta($user_id, 'cohort', $cohort);
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

    public function update_trainer($request) {
    global $wpdb;

    $params = $request->get_params();
    $user_id = $params['user_id'];

    // Retrieve existing user data
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        return new \WP_Error('user-not-found', 'User not found', array('status' => 404));
    }

    // Update user data
    $updated_user = array(
        'user_email' => $params['email'],
        'user_pass' => $params['password']
    );

    $cohort = $params['cohort'];
    $role = $params['role'];
    $lastname = $params['lastname'];

    $req = $wpdb->update(
        $wpdb->users,
        $updated_user,
        array('ID' => $user_id)
    );

    if ($req !== false) {
        update_user_meta($user_id, 'last_name', $lastname);
        update_user_meta($user_id, 'role', $role);
        update_user_meta($user_id, 'cohort', $cohort);

        $res = "Trainer Updated";
        return rest_ensure_response($res);
    } else {
        $wpdb_error = $wpdb->last_error;
        $error_message = 'Cannot update Trainer: ' . $wpdb_error;
        error_log($error_message); // Log the error message for debugging purposes

        return new \WP_Error('cant-update', $error_message, array('status' => 500));
    }
}


    function create_task_route() {
    $request_data = $_POST; 

    
    if ( empty( $request_data['task_title'] ) || empty( $request_data['task_desc'] ) || empty( $request_data['trainee'] ) ) {
        return new \WP_Error( 'missing-fields', 'Please provide all required fields.', array( 'status' => 400 ) );
    }

    // Create the task
    global $wpdb;
    $table = $wpdb->prefix . 'tasks';
    $trainer_logged_in = wp_get_current_user();
    $created_by = $trainer_logged_in->first_name . ' ' . $trainer_logged_in->last_name;
    $task_data = array(
        'task_title' => sanitize_text_field( $request_data['task_title'] ),
        'task_desc' => sanitize_textarea_field( $request_data['task_desc'] ),
        'trainee' => sanitize_text_field( $request_data['trainee'] ),
        'trainee_select' => sanitize_text_field( $request_data['trainee_select'] ),
        'duedate' => sanitize_text_field( $request_data['duedate'] ),
        'created_by' => $created_by,
    );

    $result = $wpdb->insert( $table, $task_data );
    if ( $result ) {
        return 'Task Created';
    } else {
        return new \WP_Error( 'cant-create', 'Unable to create task.', array( 'status' => 500 ) );
    }
}


    public function update_tasks($data){
        global $wpdb;
        $id = $data['id'];
        $taskTitle = $data['task-title'];
        $taskDesc = $data['task-desc'];
        $trainee = $data['trainee'];
        $traineeSelect = $data['trainee-select'];
        $duedate = $data['duedate'];
        $updated_data = $wpdb->update("{$wpdb->prefix}tasks", array('task_title' => $taskTitle, 'task_desc' => $taskDesc, 'trainee' => $trainee, 'trainee_select' => $traineeSelect, 'duedate' => $duedate), array('id' => $id));
        
        if($updated_data){
            return 'Task Updated';
        } else {
            return new \WP_Error('cant-update', 'Cant update task', array('status' => 500));
        }
    }

    public function delete_tasks($data){
        global $wpdb;
        $id = $data['id'];
        $delete = $wpdb->delete("{$wpdb->prefix}tasks", array('id' => $id));

        if($delete){
            return 'Task Deleted';
        } else {
            return new \WP_Error('cant-delete', 'Cant delete task', array('status' => 500));
        }
    }

    public function get_tasks($data){
        global $wpdb;
        $id = $data['id'];
        $tasks = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tasks WHERE id = {$id}");

        if($tasks){
            return $tasks;
        } else {
            return new \WP_Error('cant-get', 'Cant get task', array('status' => 500));
        }
    }

    public function get_all_tasks(){
        global $wpdb;
        $tasks = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tasks");

        if($tasks){
            return $tasks;
        } else {
            return new \WP_Error('cant-get', 'Cant get task', array('status' => 500));
        }
    }
 }