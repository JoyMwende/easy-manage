<?php

/**
 * @package Easy Manage Plugin
 */

namespace Inc\Pages;


class AdminRoutes
{

    public function register()
    {
        add_action('rest_api_init', array($this, 'register_admin_routes'));
    }
    public function register_admin_routes()
    {
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
                'callback' => array($this, 'get_active_project_managers'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/project-managers/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_project_manager'),
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

        register_rest_route(
            'easymanage/v1',
            '/users/(?P<id>\d+)/deactivate',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'deactivate_user'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/users/(?P<id>\d+)/activate',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'activate_user'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/activated-users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'view_activated_users'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/deactivated-users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'view_deactivated_users'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/non-deleted-pms/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'view_non_deleted_pms'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_tasks'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_task'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/total-users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_users'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/total-project-managers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_project_managers'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/total-trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_trainers'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/total-trainees/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_trainees'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/total-tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_tasks'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/deleted-users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_deleted_users'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/search-users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'search_users'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
                }
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/search-tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'search_tasks'),
                'permission_callback' => function () {
                    return current_user_can('manage_options');
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
            $user->role = array_keys($roles)[0];
            $user->is_active = get_user_meta($user->ID, 'is_active', true);
            $user->is_deleted = get_user_meta($user->ID, 'is_deleted', true);
            return $user;
        }, $users);
        $seachterm = $request->get_param('search');
        $users = array_filter($users, function ($user) use ($seachterm) {
            return strpos(strtolower($user->firstname), strtolower($seachterm)) !==false || strpos($user->email, $seachterm)!==false;
        });

        if ($users || count($users) == 0) {
            return new \WP_REST_Response($users, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Users', array('status' => 500));
        }
    }

    public function search_tasks($request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';
        $seachterm = $request->get_param('search');

        $tasks = $wpdb->get_results("SELECT * FROM $table_name WHERE is_deleted = 0");
        $tasks = array_filter($tasks, function ($task) use ($seachterm) {
            return strpos(strtolower($task->task_title), strtolower($seachterm)) !==false || strpos($task->task_desc, $seachterm)!==false;
        });

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('cant-get', 'Cannot get tasks', array('status' => 500));
        }
    }

    public function get_users()
    {
        $users = get_users(['fields' => ['ID', 'user_login', 'user_email']]);
        $users = array_map(function ($user) {
            $user->email = $user->user_email;
            unset($user->user_email);
            $user->firstname = $user->user_login;
            unset($user->user_login);
            $user->lastname = get_user_meta($user->ID, 'last_name', true);
            $roles = get_user_meta($user->ID, 'wp_capabilities', true);
            $user->role = array_keys($roles)[0];
            $user->is_active = get_user_meta($user->ID, 'is_active', true);
            $user->is_deleted = get_user_meta($user->ID, 'is_deleted', true);
            return $user;
        }, $users);

        if ($users) {
            return new \WP_REST_Response($users, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Users', array('status' => 500));
        }
    }

    public function get_user($request)
    {
        global $wpdb;
        $id = $request['id'];
        $user = $wpdb->get_results("
        SELECT users.ID AS id, users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS wp_capabilities,  meta3.meta_value AS is_active, meta4.meta_value AS is_deleted
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'wp_capabilities'
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'is_active'
            LEFT JOIN {$wpdb->usermeta} AS meta4 ON meta4.user_id = users.ID AND meta4.meta_key = 'is_deleted' WHERE users.ID = $id
        ");

        if ($user) {
            return new \WP_REST_Response($user, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get User', array('status' => 500));
        }
    }

    public function delete_user($request)
    {
        $user_id = $request['id'];
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
        }

        // Check if the user is an admin
        $user_roles = $user->roles;
        if (in_array('administrator', $user_roles)) {
            return new \WP_Error('delete_admin_not_allowed', 'Deleting admin user is not allowed', ['status' => 403]);
        }

        // Update user meta to mark the user as deleted and inactive
        $is_deleted = update_user_meta($user_id, 'is_deleted', 1);
        $is_inactive = update_user_meta($user_id, 'is_active', 0);

        if (!$is_deleted || !$is_inactive) {
            return new \WP_Error('delete_failed', 'User deletion failed', ['status' => 500]);
        }

        $response = array(
            'message' => 'User deleted successfully',
            'status' => 200
        );

        return new \WP_REST_Response($response, 200);

    }

    public function create_project_manager($request)
    {
        global $wpdb;
        $user_logged_in = wp_get_current_user();

        $params = $request->get_params();
        $user_login = $params['firstname'];
        $user_email = $params['email'];
        $user_pass = $params['password'];

        $role = $params['role'];
        $lastname = $params['lastname'];
        $created_by = $user_logged_in->user_login;

        $user_id = wp_create_user($user_login, $user_pass, $user_email);

        if (!is_wp_error($user_id)) {
            $user = get_user_by('id', $user_id);

            $user->set_role($role);
            wp_update_user($user);

            update_user_meta($user_id, 'last_name', $lastname);
            update_user_meta($user_id, 'created_by', $created_by);

            // Add is_active and is_deleted meta data
            update_user_meta($user_id, 'is_active', 1);
            update_user_meta($user_id, 'is_deleted', 0);

            $res = "Project Manager Created";
            return rest_ensure_response($res);
        } else {
            $wpdb_error = $wpdb->last_error;
            $error_message = 'Cannot create Project Manager: ' . $wpdb_error;
            error_log($error_message);

            return new \WP_Error('cant-create', $error_message, array('status' => 500));
        }
    }

    public function get_trainees()
    {
        $args = array(
            'role' => 'trainee',
            // Specify the desired role
            'orderby' => 'registered',
            'order' => 'DESC',
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
        if ($trainee_list) {
            return new \WP_REST_Response($trainee_list, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant get trainee', array('status' => 500));
        }
    }

    public function get_active_project_managers()
    {
        $project_manager_role = 'project_manager';

        $project_managers = get_users(
            array(
                'role' => $project_manager_role,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'is_active',
                        'value' => 1,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => 'is_deleted',
                        'value' => 0,
                        'compare' => '=',
                        'type' => 'NUMERIC',
                    ),
                ),
                'fields' => array('ID', 'user_login', 'lastname', 'user_email', 'role', 'created_by', 'meta_query'),
            )
        );

        if ($project_managers) {
            return new \WP_REST_Response($project_managers, 200);
        } else {
            return new \WP_Error('no_project_managers', 'No active project managers found', array('status' => 404));
        }
    }


    public function delete_project_manager($request)
    {
        $user_id = $request['id'];
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return new \WP_Error('project_manager_not_found', 'Project Manager not found', ['status' => 404]);
        }

        // Check if the user is an admin
        $user_roles = $user->roles;
        if (in_array('administrator', $user_roles)) {
            return new \WP_Error('delete_admin_not_allowed', 'Deleting admin user is not allowed', ['status' => 403]);
        }
        if (in_array('trainee', $user_roles)) {
            return new \WP_Error('delete_trainee_not_allowed', 'Deleting trainee user is not allowed', ['status' => 403]);
        }
        if (in_array('trainer', $user_roles)) {
            return new \WP_Error('delete_trainer_not_allowed', 'Deleting trainer user is not allowed', ['status' => 403]);
        }

        // Update user meta to mark the user as deleted and inactive
        $is_deleted = update_user_meta($user_id, 'is_deleted', 1);
        $is_active = update_user_meta($user_id, 'is_active', 0);

        if (!$is_deleted || !$is_active) {
            return new \WP_Error('delete_failed', 'Project Manager deletion failed', ['status' => 500]);
        }

        $response = array(
            'message' => 'Project manager deleted successfully',
            'status' => 200
        );

        return new \WP_REST_Response($response, 200);

    }


    public function get_trainers()
    {
        $args = array(
            'role' => 'trainer',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'is_deleted',
                    'value' => 0,
                    'compare' => '=',
                ),
                array(
                    'key' => 'is_active',
                    'value' => 1,
                    'compare' => '=',
                ),
            ),
        );

        $trainers = get_users($args);

        $trainer_list = array();
        foreach ($trainers as $trainer) {
            $trainer_data = array(
                'id' => $trainer->ID,
                'username' => $trainer->user_login,
                'email' => $trainer->user_email,
                'first_name' => $trainer->first_name,
                'last_name' => $trainer->last_name,
                'created_by' => get_user_meta($trainer->ID, 'created_by', true),
            );

            $trainer_list[] = $trainer_data;
        }

        if ($trainer_list) {
            return new \WP_REST_Response($trainer_list, 200);
        } else {
            return new \WP_Error('cant-get', 'Cannot get trainers', array('status' => 500));
        }
    }


    public function deactivate_user($request)
    {
        $user_id = $request['id'];
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
        }

        $is_deactivated = update_user_meta($user_id, 'is_active', 0);

        if (!$is_deactivated) {
            return new \WP_Error('deactivate_failed', 'User not deactivated', ['status' => 500]);
        }

        $response = array(
            'message' => 'User deactivated successfully',
            'status' => 200
        );

        return new \WP_REST_Response($response, 200);


    }

    public function activate_user($request)
    {
        $user_id = $request['id'];
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
        }

        $is_activated = update_user_meta($user_id, 'is_active', 1);

        if (!$is_activated) {
            return new \WP_Error('activate_failed', 'User not activated', ['status' => 500]);
        }

        $response = array(
            'message' => 'User activated successfully',
            'status' => 200
        );

        return new \WP_REST_Response($response, 200);


    }

    public function view_activated_users()
    {
        $activated_users = get_users(
            array(
                'meta_key' => 'is_active',
                'meta_value' => 1,
            )
        );

        if (empty($activated_users)) {
            return new \WP_Error('no_activated_users', 'No activated users found', ['status' => 404]);
        }

        if ($activated_users) {
            return new \WP_REST_Response($activated_users, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Activated Users', array('status' => 500));
        }
    }

    public function view_deactivated_users()
    {
        $deactivated_users = get_users(
            array(
                'meta_key' => 'is_active',
                'meta_value' => 0,
            )
        );

        if (empty($deactivated_users)) {
            return new \WP_Error('no_deactivated_users', 'No deactivated users found', ['status' => 404]);
        }

        if ($deactivated_users) {
            return new \WP_REST_Response($deactivated_users, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Deactivated Users', array('status' => 500));
        }
    }

    public function view_non_deleted_pms()
    {
        $non_deleted_pms = get_users(
            array(
                'role' => 'project_manager',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'is_active',
                        'value' => true,
                    ),
                    array(
                        'key' => 'is_deleted',
                        'value' => false,
                    ),
                ),
            )
        );

        if (empty($non_deleted_pms)) {
            return new \WP_Error('no_non_deleted_project_managers', 'No non-deleted project managers found', ['status' => 404]);
        }

        if ($non_deleted_pms) {
            return new \WP_REST_Response($non_deleted_pms, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Non Deleted Project Managers', array('status' => 500));
        }
    }

    public function get_task($request)
    {
        $task_id = $request->get_param('id');
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $task = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $task_id));

        if ($task) {
            return new \WP_REST_Response($task, 200);
        } else {
            return new \WP_Error('no_task', 'No task found', array('status' => 404));
        }
    }

    public function get_tasks()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $tasks = $wpdb->get_results("SELECT * FROM $table_name WHERE is_deleted = 0");

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('cant-get', 'Cannot get tasks', array('status' => 500));
        }
    }

    public function get_total_users()
    {
        global $wpdb;

        $total_users = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(ID) FROM {$wpdb->users} WHERE 1=1 
                AND ID NOT IN (SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'is_deleted' AND meta_value = 1)
                AND ID IN (SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'is_active' AND meta_value = 1)"
            )
        );

        if ($total_users) {
            return new \WP_REST_Response($total_users, 200);
        } else {
            return new \WP_Error('cant-get', 'Cannot get total users', array('status' => 500));
        }
    }

    public function get_total_project_managers()
    {
        $project_manager_role = 'project_manager';

        $project_managers = get_users(
            array(
                'role' => $project_manager_role,
                'fields' => array('ID', 'user_login', 'user_email', 'role'),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'last_name',
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => 'is_deleted',
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key' => 'is_deleted',
                            'value' => 0,
                        ),
                    ),
                    array(
                        'relation' => 'OR',
                        array(
                            'key' => 'is_active',
                            'compare' => 'NOT EXISTS',
                        ),
                        array(
                            'key' => 'is_active',
                            'value' => 1,
                        ),
                    ),
                ),
            )
        );

        $total_pm = count($project_managers);

        if ($total_pm) {
            return new \WP_REST_Response($total_pm, 200);
            ;
        } else {
            return new \WP_Error('cant-get', 'Cannot get total project managers', array('status' => 500));
        }
    }


    public function get_total_trainers()
    {
        $args = array(
            'role' => 'trainer',
            'orderby' => 'registered',
            'order' => 'DESC',
        );

        $trainers = get_users($args);

        $trainer_list = array();
        foreach ($trainers as $trainer) {
            $trainer_data = array(
                'id' => $trainer->ID,
                'username' => $trainer->user_login,
                'email' => $trainer->user_email,
                'first_name' => $trainer->first_name,
                'last_name' => $trainer->last_name,
                'created_by' => get_user_meta($trainer->ID, 'created_by', true),
                'is_active' => get_user_meta($trainer->ID, 'is_active', 1),
                'is_deleted' => get_user_meta($trainer->ID, 'is_deleted', 0),

            );

            $trainer_list[] = $trainer_data;
        }
        ;

        $total_trainers = count($trainer_list);

        if ($total_trainers) {
            return new \WP_REST_Response($total_trainers, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Total Trainers', array('status' => 500));
        }
    }

    public function get_total_trainees()
    {
        $args = array(
            'role' => 'trainee',
            'orderby' => 'registered',
            'order' => 'DESC',
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
                'is_active' => get_user_meta($trainee->ID, 'is_active', 1),
                'is_deleted' => get_user_meta($trainee->ID, 'is_deleted', 0),
            );

            $trainee_list[] = $trainee_data;
        }

        $total_trainees = count($trainee_list);

        if ($total_trainees) {
            return new \WP_REST_Response($total_trainees, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Total Trainees', array('status' => 500));
        }
    }

    public function get_total_tasks()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $tasks = $wpdb->get_results("SELECT * FROM $table_name WHERE is_deleted = 0");

        $total_tasks = count($tasks);

        if ($total_tasks) {
            return new \WP_REST_Response($total_tasks, 200);
        } else {
            return new \WP_Error('cant-get', 'Cant Get Total Tasks', array('status' => 500));
        }
    }

    public function get_deleted_users()
    {
        global $wpdb;

        $deleted_users = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT users.ID, users.user_login AS username, users.user_email AS email, meta1.meta_value AS first_name, meta2.meta_value AS last_name, meta3.meta_value AS is_active, meta4.meta_value AS is_deleted, meta5.meta_value AS wp_capabilities
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'first_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'is_active'
            LEFT JOIN {$wpdb->usermeta} AS meta4 ON meta4.user_id = users.ID AND meta4.meta_key = 'is_deleted'
            LEFT JOIN {$wpdb->usermeta} AS meta5 ON meta5.user_id = users.ID AND meta5.meta_key = 'wp_capabilities'
            WHERE meta3.meta_value = 0 AND meta4.meta_value = 1"
            )
        );

        if (!empty($deleted_users)) {
            return rest_ensure_response($deleted_users);
        } else {
            return new \WP_Error('cant-get', 'Cannot get deleted users', array('status' => 500));
        }
    }


}