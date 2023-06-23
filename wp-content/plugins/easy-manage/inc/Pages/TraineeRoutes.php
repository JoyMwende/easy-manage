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
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/assignedtasks/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_task'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/assignedtasks/(?P<id>\d+)/markcomplete',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'mark_task_complete'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/completedtasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_completed_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/assignedtasks/(?P<id>\d+)/markstarted',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'mark_task_started'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/startedtasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_started_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );
    }

    public function get_tasks($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tasks';

    // $user_id = get_current_user_id(); 
    $tasks = $wpdb->get_results("SELECT * FROM $table_name /*WHERE trainee = %d*/", /*$user_id*/);
    
    if ($tasks) {
        return $tasks;
    } else {
        return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
    }
}

//     public function get_tasks($request) {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'tasks';

//     $user_id = get_current_user_id();
//     $user = get_userdata($user_id);

//     if (!$user) {
//         return new \WP_Error('user_not_found', 'User not found', array('status' => 404));
//     }

//     $trainee_first_name = $user->user_login;

//     $tasks = $wpdb->get_results(
//         $wpdb->prepare(
//             "SELECT * FROM $table_name WHERE trainee = %s",
//             $trainee_first_name
//         )
//     );

//     if ($tasks) {
//         return $tasks;
//     } else {
//         return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
//     }
// }



    public function get_task($request) {
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



    public function mark_task_started($request) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';
        $task_id = $request->get_param('id');

        // Retrieve the current status of the task
        $current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM $table_name WHERE id = %d", $task_id));

        if ($current_status === 'Not Started') {
            // Update the task status to "In Progress"
            $result = $wpdb->update($table_name, array('status' => 'In Progress'), array('id' => $task_id));

            if ($result !== false) {
                return 'Task marked as in progress';
            } else {
                return new \WP_Error('cant-update', 'Unable to mark task as in progress.', array('status' => 500));
            }
        } else {
            // Task is already in progress or has a different status
            return new \WP_Error('invalid-status', 'Task cannot be marked as in progress. Invalid status.', array('status' => 400));
        }
    }




    public function mark_task_complete($request) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';
        $task_id = $request->get_param('id');

        $current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM $table_name WHERE id = %d", $task_id));

        if ($current_status === 'In Progress') {
            $result = $wpdb->update($table_name, array('status' => 'Completed'), array('id' => $task_id));

            if ($result !== false) {
                return 'Task marked as completed';
            } else {
                return new \WP_Error('cant-update', 'Unable to mark task as completed.', array('status' => 500));
            }
        } else {
            return new \WP_Error('invalid-status', 'Task cannot be marked as completed. Invalid status.', array('status' => 400));
        }
    }

    public function get_started_tasks() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tasks';

    $completed_tasks = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'In Progress'");

    if ($completed_tasks) {
        return $completed_tasks;
    } else {
        return new \WP_Error('no_started_tasks', 'No started tasks found', ['status' => 404]);
    }
}

    public function get_completed_tasks() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tasks';

    $completed_tasks = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'Completed'");

    if ($completed_tasks) {
        return $completed_tasks;
    } else {
        return new \WP_Error('no_completed_tasks', 'No completed tasks found', ['status' => 404]);
    }
}


 }