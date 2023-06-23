<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;


 class CohortRoutes{

    public function register(){
        add_action('rest_api_init', array($this, 'register_cohort_routes'));
    }

    public function register_cohort_routes(){
        register_rest_route(
            'easymanage/v5',
            '/cohorts/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_cohorts'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v5',
            '/cohorts/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_cohort'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v5',
            '/cohorts/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_cohort'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v5',
            '/cohorts/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_cohort'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );

        register_rest_route(
            'easymanage/v5',
            '/cohorts',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_cohort'),
                'permission_callback' => function () {
                    return current_user_can('project_manager');
                }
            )
        );
    }

    public function get_cohorts(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cohorts';
        $cohorts = $wpdb->get_results("SELECT * FROM $table_name");
        return $cohorts;
    }

    public function get_cohort($data){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cohorts';
        $id = $data['id'];
        $cohort = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $id");
        return $cohort;
    }

    public function delete_cohort($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cohorts';
    $id = $data['id'];
    
    $result = $wpdb->update($table_name, array('is_deleted' => 1), array('id' => $id));
    
    if ($result !== false) {
        return 'Cohort Deleted';
    } else {
        return new \WP_Error('cant-delete', 'Unable to delete cohort.', array('status' => 500));
    }
}


    public function create_cohort($request) {
        $request_data = $request->get_params();

        $required_fields = ['cohort_name', 'location', 'cohort_trainer', 'languages', 'startdate', 'enddate'];
        foreach ($required_fields as $field) {
            if (empty($request_data[$field])) {
                return new \WP_Error('missing-fields', 'Please provide all required fields.', array('status' => 400));
            }
        }

        global $wpdb;
        $table = $wpdb->prefix . 'cohorts';
        $cohort_data = array(
            'cohort_name' => sanitize_text_field($request_data['cohort_name']),
            'location' => sanitize_textarea_field($request_data['location']),
            'cohort_trainer' => sanitize_text_field($request_data['cohort_trainer']),
            'languages' => sanitize_text_field($request_data['languages']),
            'startdate' => sanitize_text_field($request_data['startdate']),
            'enddate' => sanitize_text_field($request_data['enddate'])
        );

        $result = $wpdb->insert($table, $cohort_data);
        if ($result !== false) {
            return 'Cohort Created';
        } else {
            return new \WP_Error('cant-create', 'Unable to create cohort.', array('status' => 500));
        }
    }

    public function update_cohort($request) {
        $request_data = $request->get_params();
        
        // Check for missing fields
        $required_fields = ['id', 'cohort_name', 'location', 'cohort_trainer', 'languages', 'startdate', 'enddate'];
        foreach ($required_fields as $field) {
            if (empty($request_data[$field])) {
                return new \WP_Error('missing-fields', 'Please provide all required fields.', array('status' => 400));
            }
        }
        
        // Update the task
        global $wpdb;
        $table = $wpdb->prefix . 'cohorts';
        $cohort_id = sanitize_text_field($request_data['id']);
        
        $cohort_data = array(
            'cohort_name' => sanitize_text_field($request_data['cohort_name']),
            'location' => sanitize_textarea_field($request_data['location']),
            'cohort_trainer' => sanitize_text_field($request_data['cohort_trainer']),
            'languages' => sanitize_text_field($request_data['languages']),
            'startdate' => sanitize_text_field($request_data['startdate']),
            'enddate' => sanitize_text_field($request_data['enddate']),
        );
        
        $result = $wpdb->update($table, $cohort_data, array('id' => $cohort_id));
        if ($result !== false) {
            return 'Cohort Updated';
        } else {
            return new \WP_Error('cant-update', 'Unable to update cohort.', array('status' => 500));
        }
    }

}