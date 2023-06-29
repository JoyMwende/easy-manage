<?php

/**
 * @package Easy Manage Plugin
 */

namespace Inc\Pages;

use WP_Error;


class PmRoutes
{
    public function register()
    {
        add_action('rest_api_init', array($this, 'register_pm_routes'));
    }
    public function register_pm_routes()
    {
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
            '/deleted-trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_deleted_trainers'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_tasks'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_task'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/total-trainers/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_trainers'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/total-trainees/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_trainees'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/total-tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_total_tasks'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/latest-created-tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_latest_created_tasks'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/search-users/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'search_users'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v2',
            '/search-tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'search_tasks'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
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

    public function search_tasks($request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';
        $seachterm = $request->get_param('search');

        $tasks = $wpdb->get_results("SELECT * FROM $table_name WHERE is_deleted = 0");
        $tasks = array_filter($tasks, function ($task) use ($seachterm) {
            return strpos(strtolower($task->task_title), strtolower($seachterm)) !== false || strpos($task->task_desc, $seachterm) !== false;
        });

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('cant-get', 'Cannot get tasks', array('status' => 500));
        }
    }


    public function get_trainers()
    {
        global $wpdb;

        $args = array(
            'role' => 'trainer',
            // Specify the desired role
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
            'orderby' => 'registered',
            'order' => 'DESC',
        );

        $trainers = get_users($args);

        $trainer_list = array();
        foreach ($trainers as $trainer) {
            $user_login = $trainer->user_login;
            $last_name = get_user_meta($trainer->ID, 'last_name', true);

            $cohort_trainer = $user_login . ' ' . $last_name;

            $cohort_name = $wpdb->get_var($wpdb->prepare(
                "SELECT cohort_name FROM {$wpdb->prefix}cohorts WHERE cohort_trainer = %s", $cohort_trainer
            )
            );


            $trainer_data = array(
                'id' => $trainer->ID,
                'firstname' => $trainer->user_login,
                'email' => $trainer->user_email,
                'lastname' => $trainer->last_name,
                'created_by' => get_user_meta($trainer->ID, 'created_by', true),
                'cohort_name' => $cohort_name,
            );

            $trainer_list[] = $trainer_data;
        }

        if ($trainer_list) {
            return $trainer_list;
        } else {
            return new \WP_Error('cant-get', 'Unable to retrieve trainers', array('status' => 500));
        }
    }




    public function get_trainer($request)
    {
        $trainer_id = $request->get_param('id');
        $trainer = get_user_by('ID', $trainer_id);

        if ($trainer) {
            $trainer_data = array(
                'email' => $trainer->user_email,
                'firstname' => $trainer->user_login,
                'lastname' => $trainer->last_name,
                'created_by' => get_user_meta($trainer_id, 'created_by', true),
                'is_active' => get_user_meta($trainer_id, 'is_active', true),
                'is_deleted' => get_user_meta($trainer_id, 'is_deleted', true),
                'role' => $trainer->roles[0],
            );

            return rest_ensure_response($trainer_data);
        } else {
            return new \WP_Error('trainer-not-found', 'Trainer not found', array('status' => 404));
        }
    }

    public function delete_trainer($request)
    {
        $user_id = $request['id'];
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return new \WP_Error('user_not_found', 'User not found', ['status' => 404]);
        }

        $user_roles = $user->roles;
        if (in_array('administrator', $user_roles)) {
            return new \WP_Error('delete_admin_not_allowed', 'Deleting admin user is not allowed', ['status' => 403]);
        }
        if (in_array('project_manager', $user_roles)) {
            return new \WP_Error('delete_project_manager_not_allowed', 'Deleting project manager is not allowed', ['status' => 403]);
        }

        $is_deleted = update_user_meta($user_id, 'is_deleted', 1);
        $is_inactive = update_user_meta($user_id, 'is_active', 0);

        if (!$is_deleted || !$is_inactive) {
            return new \WP_Error('delete_failed', 'User deletion failed', ['status' => 500]);
        }

        $response = array(
            'message' => 'Trainer deleted successfully',
            'status' => 200
        );

        return new \WP_REST_Response($response, 200);

    }

    public function update_trainer($request)
    {
        global $wpdb;

        $params = $request->get_params();
        $trainer_id = $params['id'];
        $user_login = $params['firstname'];
        $user_email = $params['email'];
        $user_pass = $params['password'];
        $role = $params['role'];
        $lastname = $params['lastname'];
        $created_by = $params['created_by'];
        $cohort_name = $params['cohort_name']; // Added cohort name parameter

        $user_data = array(
            'ID' => $trainer_id,
            'user_login' => $user_login,
            'user_email' => $user_email,
            'user_pass' => $user_pass,
            'role' => $role,
        );

        $updated = wp_update_user($user_data);

        if (is_wp_error($updated)) {
            $error_message = $updated->get_error_message();
            return new \WP_Error('update-error', $error_message, array('status' => 500));
        }

        update_user_meta($trainer_id, 'lastname', $lastname);
        update_user_meta($trainer_id, 'created_by', $created_by);
        update_user_meta($trainer_id, 'cohort_assigned', $cohort_name);


        $table = $wpdb->prefix . 'cohorts';
        $wpdb->update(
            $table,
            array('cohort_trainer' => $user_login),
            array('cohort_name' => $cohort_name)
        );

        $res = "Trainer updated";
        return rest_ensure_response($res);
    }





    // Create a trainer and assign to a cohort
    public function create_trainer($request)
    {
        global $wpdb;
        $user_logged_in = wp_get_current_user();

        $params = $request->get_params();
        $user_login = $params['firstname'];
        $user_email = $params['email'];
        $user_pass = $params['password'];

        $role = $params['role'];
        $lastname = $params['lastname'];
        $cohort_name = $params['cohort_name'];
        $created_by = $user_logged_in->user_login . ' ' . $user_logged_in->last_name;

        $user_id = wp_create_user($user_login, $user_pass, $user_email);

        if (!is_wp_error($user_id)) {
            $user = get_user_by('id', $user_id);

            $user->set_role($role);
            wp_update_user($user);

            update_user_meta($user_id, 'last_name', $lastname);
            update_user_meta($user_id, 'created_by', $created_by);

            update_user_meta($user_id, 'is_active', 1);
            update_user_meta($user_id, 'is_deleted', 0);

            // $trainer_name = $user_logged_in->first_name . ' ' . $user_logged_in->last_name;

            $table = $wpdb->prefix . 'cohorts';
            $wpdb->update(
                $table,
                array('cohort_trainer' => $user_login . ' ' . $lastname),
                array('cohort_name' => $cohort_name)
            );

            $res = "Trainer Created and assigned to Cohort";
            return rest_ensure_response($res);
        } else {
            $wpdb_error = $wpdb->last_error;
            $error_message = 'Cannot create Trainer: ' . $wpdb_error;
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
                'firstname' => $trainee->user_login,
                'email' => $trainee->user_email,
                'lastname' => get_user_meta($trainee->ID, 'last_name', true),
                'cohort' => get_user_meta($trainee->ID, 'cohort', true),
                'created_by' => get_user_meta($trainee->ID, 'created_by', true),
            );

            $trainee_list[] = $trainee_data;
        }
        if ($trainee_list) {
            return $trainee_list;
        } else {
            return new \WP_Error('cant-get', 'Cant get trainee', array('status' => 500));
        }
    }


    public function get_trainee($request)
    {
        $trainee_id = $request->get_param('id');
        $trainee = get_user_by('ID', $trainee_id);

        if ($trainee) {
            $trainee_data = array(
                'firstname' => $trainee->user_login,
                'email' => $trainee->user_email,
                'lastname' => $trainee->last_name,
                'cohort' => get_user_meta($trainee_id, 'cohort', true),
                'created_by' => get_user_meta($trainee_id, 'created_by', true),
                'role' => $trainee->roles[0], // Assuming the trainee has only one role
            );

            return rest_ensure_response($trainee_data);
        } else {
            return new \WP_Error('trainee-not-found', 'Trainee not found', array('status' => 404));
        }
    }

    public function get_deleted_trainers()
    {
        $args = array(
            'role' => 'trainer',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'is_deleted',
                    'value' => 1,
                    'compare' => '=',
                ),
                array(
                    'key' => 'is_active',
                    'value' => 0,
                    'compare' => '=',
                ),
            ),
            'orderby' => 'registered',
            'order' => 'DESC',
        );

        $trainers = get_users($args);

        $deleted_trainers = array();
        foreach ($trainers as $trainer) {
            $trainer_data = array(
                'id' => $trainer->ID,
                'username' => $trainer->user_login,
                'email' => $trainer->user_email,
                'first_name' => $trainer->first_name,
                'last_name' => $trainer->last_name,
                'created_by' => get_user_meta($trainer->ID, 'created_by', true),
                'is_active' => get_user_meta($trainer->ID, 'is_active', true),
                'is_deleted' => get_user_meta($trainer->ID, 'is_deleted', true),
            );

            $deleted_trainers[] = $trainer_data;
        }

        if ($deleted_trainers) {
            return $deleted_trainers;
        } else {
            return new \WP_Error('cant-get', 'Unable to retrieve deleted trainers', array('status' => 500));
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

    public function get_latest_created_tasks($request)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tasks';

        $tasks = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY id DESC LIMIT 5"
        );

        if ($tasks) {
            return $tasks;
        } else {
            return new \WP_Error('no_tasks', 'No tasks found', array('status' => 404));
        }
    }


}