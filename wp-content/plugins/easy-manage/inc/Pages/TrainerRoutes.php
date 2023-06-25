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

        register_rest_route(
            'easymanage/v4',
            '/trainees/(?P<id>\d+)/activate',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'activate_trainee'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/(?P<id>\d+)/deactivate',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'deactivate_trainee'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/activated',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_activated_trainees'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/deactivated',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_deactivated_trainees'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );

        register_rest_route(
            'easymanage/v4',
            '/trainees/non-deleted',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'view_non_deleted_trainees'),
                'permission_callback' => function () {
                    return current_user_can('trainer');
                }
            )
        );
    }


    public function get_trainees()
    {
        $args = array(
            'role' => 'trainee',
            // Specify the desired role
            'orderby' => 'registered',
            'order' => 'DESC',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'is_deleted',
                    'value' => 0,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
                array(
                    'key' => 'is_active',
                    'value' => 1,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
            ),
        );

        $trainees = get_users($args);

        $trainee_list = array();
        foreach ($trainees as $trainee) {
            $trainee_data = array(
                'id' => $trainee->ID,
                'username' => $trainee->user_login,
                'email' => $trainee->user_email,
                'first_name' => $trainee->first_name,
                'last_name' => $trainee->last_name,
                'cohort' => get_user_meta($trainee->ID, 'cohort', true),
                'created_by' => get_user_meta($trainee->ID, 'created_by', true),
            );

            $trainee_list[] = $trainee_data;
        }

        if (!empty($trainee_list)) {
            return $trainee_list;
        } else {
            return new \WP_Error('cant-get', 'Cannot get trainees', array('status' => 500));
        }
    }



    public function get_trainee($request) {
        $trainee_id = $request->get_param('id');
    $trainee = get_user_by('ID', $trainee_id);

    if ($trainee) {
        $trainee_data = array(
            'id' => $trainee->ID,
            'user_login' => $trainee->user_login,
            'user_email' => $trainee->user_email,
            'first_name' => $trainee->first_name,
            'last_name' => $trainee->last_name,
            'cohort' => get_user_meta($trainee_id, 'cohort', true),
            'created_by' => get_user_meta($trainee_id, 'created_by', true),
            'role' => $trainee->roles[0],
        );

        return rest_ensure_response($trainee_data);
    } else {
        return new \WP_Error('trainee-not-found', 'Trainee not found', array('status' => 404));
    }
}

    public function delete_trainee($request)
    {
        $user_id = $request['id'];
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return new \WP_Error('trainee_not_found', 'Trainee not found', ['status' => 404]);
        }

        $user_roles = $user->roles;
        if (in_array('administrator', $user_roles)) {
            return new \WP_Error('delete_admin_not_allowed', 'Deleting admin user is not allowed', ['status' => 403]);
        }
        if (in_array('project_manager', $user_roles)) {
            return new \WP_Error('delete_project_manager_not_allowed', 'Deleting project manager user is not allowed', ['status' => 403]);
        }
        if (in_array('trainer', $user_roles)) {
            return new \WP_Error('delete_trainer_not_allowed', 'Deleting trainer is not allowed', ['status' => 403]);
        }

        $is_deleted = update_user_meta($user_id, 'is_deleted', 1);
        $is_active = update_user_meta($user_id, 'is_active', 0);

        if (!$is_deleted || !$is_active) {
            return new \WP_Error('delete_failed', 'Trainee deletion failed', ['status' => 500]);
        }

        return 'Trainee deleted successfully';
    }

    public function create_trainee($request)
    {
        $user_logged_in = wp_get_current_user();
        $params = $request->get_params();

        $user_login = $params['firstname'];
        $user_email = $params['email'];
        $user_pass = $params['password'];

        $role = $params['role'];
        $lastname = $params['lastname'];
        $cohort = $params['cohort'];
        $created_by = $user_logged_in->user_login . ' ' . $user_logged_in->last_name;

        $user_id = wp_create_user($user_login, $user_pass, $user_email);

        if (!is_wp_error($user_id)) {
            $user = get_user_by('id', $user_id);

            $user->set_role($role);
            wp_update_user($user);

            update_user_meta($user_id, 'last_name', $lastname);
            update_user_meta($user_id, 'created_by', $created_by);
            update_user_meta($user_id, 'cohort', $cohort);

            update_user_meta($user_id, 'is_active', 1);
            update_user_meta($user_id, 'is_deleted', 0);

            $res = "Trainee Created Successfully";
            return rest_ensure_response($res);
        } else {
            $error_message = 'Cannot create Trainee: ' . $user_id->get_error_message();
            error_log($error_message);

            return new \WP_Error('cant-create', $error_message, array('status' => 500));
        }
    }




    public function update_trainee($request) {
    $params = $request->get_params();
    $trainee_id = $params['id'];

    $user_login = sanitize_text_field($params['firstname']);
    $user_email = sanitize_email($params['email']);
    $user_pass = sanitize_text_field($params['password']);

    $cohort = sanitize_text_field($params['cohort']);
    $role = sanitize_text_field($params['role']);
    $lastname = sanitize_text_field($params['lastname']);

  
    $user = get_user_by('ID', $trainee_id);

    if ($user) {
        $user->user_login = $user_login;
        $user->user_email = $user_email;
        $user->user_pass = $user_pass;

     
        update_user_meta($trainee_id, 'last_name', $lastname);
        update_user_meta($trainee_id, 'cohort', $cohort);

       
        $user->set_role($role);
        wp_update_user($user);

        $res = "Trainee Updated";
        return rest_ensure_response($res);
    } else {
        $error_message = 'Trainee not found.';
        error_log($error_message);

        return new \WP_Error('not-found', $error_message, array('status' => 404));
    }
}

    public function create_tasks($request)
    {
        $request_data = $request->get_params();

        $required_fields = ['task_title', 'task_desc', 'trainee', 'duedate'];
        foreach ($required_fields as $field) {
            if (empty($request_data[$field])) {
                return new \WP_Error('missing-fields', 'Please provide all required fields.', array('status' => 400));
            }
        }

        $trainees = $request_data['trainee'];
        if (empty($trainees) || !is_array($trainees)) {
            return new \WP_Error('invalid-data', 'Invalid trainees data.', array('status' => 400));
        }

        $max_assigned_tasks = 3; 
        $trainees_with_max_tasks = array();
        foreach ($trainees as $trainee) {
            $assigned_tasks_count = $this->get_assigned_tasks_count($trainee);
            if ($assigned_tasks_count >= $max_assigned_tasks) {
                $trainees_with_max_tasks[] = $trainee;
            }
        }

        if (!empty($trainees_with_max_tasks)) {
            $error_message = 'The following trainees have reached the maximum number of assigned tasks: ' . implode(', ', $trainees_with_max_tasks);
            return new \WP_Error('max-tasks-reached', $error_message, array('status' => 400));
        }

        // Create the task
        global $wpdb;
        $table = $wpdb->prefix . 'tasks';
        $trainer_logged_in = wp_get_current_user();
        $created_by = $trainer_logged_in->first_name . ' ' . $trainer_logged_in->last_name;
        $task_data = array(
            'task_title' => sanitize_text_field($request_data['task_title']),
            'task_desc' => sanitize_textarea_field($request_data['task_desc']),
            'trainee' => implode(', ', $trainees),
            // Store trainees as comma-separated values
            'duedate' => sanitize_text_field($request_data['duedate']),
            'created_by' => $created_by,
        );

        $result = $wpdb->insert($table, $task_data);
        if ($result !== false) {
            return 'Task Created';
        } else {
            return new \WP_Error('cant-create', 'Unable to create task.', array('status' => 500));
        }
    }

    private function get_assigned_tasks_count($trainee)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'tasks';
        $query = $wpdb->prepare("SELECT COUNT(*) FROM $table WHERE FIND_IN_SET(%s, trainee)", $trainee);
        return $wpdb->get_var($query);
    }

    public function update_tasks($request) {
    $request_data = $request->get_params();
    
    $required_fields = ['id', 'task_title', 'task_desc', 'trainee', 'trainee_select', 'duedate'];
    foreach ($required_fields as $field) {
        if (empty($request_data[$field])) {
            return new \WP_Error('missing-fields', 'Please provide all required fields.', array('status' => 400));
        }
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'tasks';
    $task_id = sanitize_text_field($request_data['id']);
    
    $task_data = array(
        'task_title' => sanitize_text_field($request_data['task_title']),
        'task_desc' => sanitize_textarea_field($request_data['task_desc']),
        'trainee' => sanitize_text_field($request_data['trainee']),
        'trainee_select' => sanitize_text_field($request_data['trainee_select']),
        'duedate' => sanitize_text_field($request_data['duedate']),
    );
    
    $result = $wpdb->update($table, $task_data, array('id' => $task_id));
    if ($result !== false) {
        return 'Task Updated';
    } else {
        return new \WP_Error('cant-update', 'Unable to update task.', array('status' => 500));
    }
}



   public function delete_tasks($data) {
    global $wpdb;
    $id = $data['id'];

    $table_name = $wpdb->prefix . 'tasks';

    // Check if the task status is completed
    $task_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM $table_name WHERE id = %d", $id));

    if ($task_status === 'Completed') {
        return new \WP_Error('delete_completed_task_not_allowed', 'Deleting a completed task is not allowed.', ['status' => 403]);
    }

    // Update the is_deleted column to mark the task as deleted
    $result = $wpdb->update($table_name, array('is_deleted' => 1), array('id' => $id));

    if ($result !== false) {
        return 'Task Deleted';
    } else {
        return new \WP_Error('cant-delete', 'Unable to delete task.', array('status' => 500));
    }
}


    public function get_tasks($request) {
        $task_id = $request->get_param('id');
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $task = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $task_id));

        if ($task) {
            return $task;
        } else {
            return new \WP_Error('no_task', 'No task found', array('status' => 404));
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

    public function activate_trainee($request) {
    $user_id = $request['id'];
    $user = get_user_by('ID', $user_id);

    if (!$user) {
        return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
    }

    // Check if the user is a trainee
    $user_roles = $user->roles;
    if (!in_array('trainee', $user_roles)) {
        return new \WP_Error('invalid_user_role', 'Invalid user role', ['status' => 400]);
    }

    $is_activated = update_user_meta($user_id, 'is_active', true);

    if (!$is_activated) {
        return new \WP_Error('activate_failed', 'Trainee not activated', ['status' => 500]);
    }

    return 'Trainee activated successfully';
}

public function deactivate_trainee($request) {
    $user_id = $request['id'];
    $user = get_user_by('ID', $user_id);

    if (!$user) {
        return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
    }

    // Check if the user is a trainee
    $user_roles = $user->roles;
    if (!in_array('trainee', $user_roles)) {
        return new \WP_Error('invalid_user_role', 'Invalid user role', ['status' => 400]);
    }

    $is_deactivated = update_user_meta($user_id, 'is_active', false);

    if (!$is_deactivated) {
        return new \WP_Error('deactivate_failed', 'Trainee not deactivated', ['status' => 500]);
    }

    return 'Trainee deactivated successfully';
}

public function get_activated_trainees() {
    $activated_trainees = get_users(array(
        'role' => 'trainee',
        'meta_key' => 'is_active',
        'meta_value' => true,
    ));

    if (empty($activated_trainees)) {
        return new \WP_Error('no_activated_trainees', 'No activated trainees found', ['status' => 404]);
    }

    return $activated_trainees;
}

public function get_deactivated_trainees() {
    $deactivated_trainees = get_users(array(
        'role' => 'trainee',
        'meta_key' => 'is_active',
        'meta_value' => false,
    ));

    if (empty($deactivated_trainees)) {
        return new \WP_Error('no_deactivated_trainees', 'No deactivated trainees found', ['status' => 404]);
    }

    return $deactivated_trainees;
}

    public function view_non_deleted_trainees() {
    $non_deleted_trainees = get_users(array(
        'role' => 'trainee',
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

    if (empty($non_deleted_trainees)) {
        return new \WP_Error('no_non_deleted_trainees', 'No non-deleted trainees found', ['status' => 404]);
    }

    if($non_deleted_trainees){
        return $non_deleted_trainees;
    } else {
        return new \WP_Error('cant-get', 'Cant get trainee', array('status' => 500));
    }
}


 }