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
                'callback' => array($this, 'get_assigned_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/newesttasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_newest_tasks'),
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

        register_rest_route(
            'easymanage/v3',
            '/assignedtasks/count',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'count_assigned_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/startedtasks/count',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'count_in_progress_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/completedtasks/count',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'count_completed_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/assignedtasks/countgroup',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'count_group_tasks'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );

        register_rest_route(
            'easymanage/v3',
            '/search-users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'search_users'),
                'permission_callback' => function () {
                    return current_user_can('trainee');
                }
            )
        );
    }

    public function search_users($request)
    {
        $users = get_users(['fields' => ['ID', 'user_login', 'user_email']]);
        $users = array_map(function ($user) {
            $user->email = $user->user_email;
            unset($user->user_email);
            $user->firstname = $user->user_login;
            unset($user->user_login);
            $user->lastname = get_user_meta($user->ID, 'last_name', true);
            $roles = get_user_meta($user->ID, 'wp_capabilities', true);
            $user->role = array_keys($roles);
            $user->is_active = get_user_meta($user->ID, 'is_active', true);
            $user->is_deleted = get_user_meta($user->ID, 'is_deleted', true);
            return $user;
        }, $users);
        $seachterm = $request->get_param('search');
        $users = array_filter($users, function ($user) use ($seachterm) {
            return strpos(strtolower($user->firstname), strtolower($seachterm)) !== false || strpos($user->email, $seachterm) !== false;
        });

        if ($users || count($users) == 0) {
            return new \WP_REST_Response($users, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Users', array('status' => 500));
        }
    }

    public function get_assigned_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $tasks = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE FIND_IN_SET(%s, trainee) AND status != 'Completed'",
            $user_login . ' ' . $last_name
        )
        );

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
        }
    }

    public function get_newest_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $tasks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE FIND_IN_SET(%s, trainee) ORDER BY id DESC LIMIT 3",
                $user_login . ' ' . $last_name
            )
        );

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
        }
    }


    public function get_task($request)
    {
        $task_id = $request['id'];
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $task = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d AND trainee = CONCAT(%s, ' ', %s)",
            $task_id,
            $user_login,
            $last_name
        )
        );

        if ($task) {
            return $task;
        } else {
            return new \WP_Error('task_not_found', 'Task not found', array('status' => 404));
        }
    }

    public function mark_task_started($request)
    {
        $task_id = $request['id'];
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $task = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d AND trainee = CONCAT(%s, ' ', %s)",
                $task_id,
                $user_login,
                $last_name
            )
        );

        if ($task && $task->status == 'Not Started') {
            $result = $wpdb->update(
                $table_name,
                array('status' => 'In Progress'),
                array('id' => $task_id, 'trainee' => $user_login . ' ' . $last_name),
                array('%s'),
                array('%d', '%s')
            );


            if ($result !== false) {
                return 'Task marked as started';
            } else {
                return new \WP_Error('mark_as_started_error', 'Unable to mark task as started', array('status' => 500));
            }
        } elseif ($task && $task->status !== 'Not Started') {
            return new \WP_Error('already_started_error', 'Task is already in progress', array('status' => 400));
        } else {
            return new \WP_Error('task_not_found', 'Task not found', array('status' => 404));
        }
    }


    public function mark_task_complete($request)
    {
        $task_id = $request['id'];
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $task = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d AND trainee = CONCAT(%s, ' ', %s)",
                $task_id,
                $user_login,
                $last_name
            )
        );

        if ($task && $task->status == 'In Progress') {
            $result = $wpdb->update(
                $table_name,
                array('status' => 'Completed'),
                array('id' => $task_id, 'trainee' => $user_login . ' ' . $last_name),
                array('%s'),
                array('%d', '%s')
            );

            
            if ($result !== false) {
                return 'Task marked as completed';
            } else {
                return new \WP_Error('mark_as_started_error', 'Unable to mark task as started', array('status' => 500));
            }
        } elseif ($task && $task->status !== 'In Progress') {
            return new \WP_Error('already_started_error', 'Task is not in progress', array('status' => 400));
        } else {
            return new \WP_Error('task_not_found', 'Task not found', array('status' => 404));
        }
    }


    public function get_started_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $tasks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE FIND_IN_SET(%s, trainee) AND status = 'In Progress'",
                $user_login . ' ' . $last_name
            )
        );

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
        }
    }


    public function get_completed_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $tasks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE FIND_IN_SET(%s, trainee) AND status = 'Completed'",
                $user_login . ' ' . $last_name
            )
        );

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
        }
    }

    public function count_assigned_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE FIND_IN_SET(%s, trainee)",
            $user_login . ' ' . $last_name
        )
        );

        if ($count !== null) {
            return $count;
        } else {
            return new \WP_Error('no_count', 'Unable to count tasks', array('status' => 500));
        }
    }

    public function count_in_progress_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE FIND_IN_SET(%s, trainee) AND status = %s",
            $user_login . ' ' . $last_name,
            'In Progress'
        )
        );

        if ($count !== null) {
            return $count;
        } else {
            return new \WP_Error('no_count', 'Unable to count tasks', array('status' => 500));
        }
    }

    public function count_completed_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE FIND_IN_SET(%s, trainee) AND status = %s",
            $user_login . ' ' . $last_name,
            'Completed'
        )
        );

        if ($count !== null) {
            return $count;
        } else {
            return new \WP_Error('no_count', 'Unable to count tasks', array('status' => 500));
        }
    }

    public function count_group_tasks($request)
    {
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $last_name = get_user_meta($current_user->ID, 'last_name', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE trainee LIKE %s AND trainee NOT LIKE %s",
            '%"' . $user_login . ' ' . $last_name . '"%',
            '%"' . $user_login . ' ' . $last_name . '"'
        )
        );

        if ($count !== null) {
            return $count;
        } else {
            return new \WP_Error('no_count', 'Unable to count tasks', array('status' => 500));
        }
    }


}