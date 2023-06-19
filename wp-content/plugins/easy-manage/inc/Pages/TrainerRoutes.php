<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;

 class TrainerRoutes{

    public function register_trainer_routes(){
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
            '/trainees/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_trainee')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainees/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_trainee')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/trainees/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_trainee')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/tasks/',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'create_tasks')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'PUT',
                'callback' => array($this, 'update_tasks')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'delete_tasks')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/tasks/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_tasks')
            )
        );

        register_rest_route(
            'easymanage/v1',
            '/tasks/',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_all_tasks')
            )
        );
    }

    public function get_trainees(){
        global $wpdb;
        $trainees = $wpdb->get_results("
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' 
        ");
        return $trainees;
    }
    
    public function get_trainee($data){
        global $wpdb;
        $id = $data['id'];
        $trainee = $wpdb->get_results("
            SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
            FROM {$wpdb->users} AS users
            LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
            LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
            LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' AND users.ID = {$id}
        ");
        return $trainee;
    }

    public function delete_trainee($data){
        global $wpdb;
        $id = $data['id'];
        $wpdb->delete("{$wpdb->users}", array('ID' => $id));
        return 'Trainee Deleted';
    }

    public function update_trainee($data){
        global $wpdb;
        $id = $data['id'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $role = $data['role'];
        $cohort = $data['cohort'];
        $wpdb->update("{$wpdb->users}", array('user_login' => $firstname, 'user_email' => $email), array('ID' => $id));
        $wpdb->update("{$wpdb->usermeta}", array('meta_value' => $lastname), array('user_id' => $id, 'meta_key' => 'last_name'));
        $wpdb->update("{$wpdb->usermeta}", array('meta_value' => $role), array('user_id' => $id, 'meta_key' => 'role'));
        $wpdb->update("{$wpdb->usermeta}", array('meta_value' => $cohort), array('user_id' => $id, 'meta_key' => 'cohort'));
        return 'Trainee Updated';
    }

    public function create_trainee($data){
        global $wpdb;
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $role = $data['role'];
        $cohort = $data['cohort'];
        $password = $data['password'];
        $wpdb->insert("{$wpdb->users}", array('user_login' => $firstname, 'user_email' => $email, 'user_pass' => $password));
        $lastid = $wpdb->insert_id;
        $wpdb->insert("{$wpdb->usermeta}", array('user_id' => $lastid, 'meta_key' => 'last_name', 'meta_value' => $lastname));
        $wpdb->insert("{$wpdb->usermeta}", array('user_id' => $lastid, 'meta_key' => 'role', 'meta_value' => $role));
        $wpdb->insert("{$wpdb->usermeta}", array('user_id' => $lastid, 'meta_key' => 'cohort', 'meta_value' => $cohort));
        return 'Trainee Created';
    }

    public function create_tasks($data){
        global $wpdb;
        $taskTitle = $data['task-title'];
        $taskDesc = $data['task-desc'];
        $trainee = $data['trainee'];
        $traineeSelect = $data['trainee-select'];
        $duedate = $data['duedate'];
        $wpdb->insert("{$wpdb->prefix}tasks", array('task_title' => $taskTitle, 'task_desc' => $taskDesc, 'trainee' => $trainee, 'trainee_select' => $traineeSelect, 'duedate' => $duedate));
        return 'Task Created';
    }

    public function update_tasks($data){
        global $wpdb;
        $id = $data['id'];
        $taskTitle = $data['task-title'];
        $taskDesc = $data['task-desc'];
        $trainee = $data['trainee'];
        $traineeSelect = $data['trainee-select'];
        $duedate = $data['duedate'];
        $wpdb->update("{$wpdb->prefix}tasks", array('task_title' => $taskTitle, 'task_desc' => $taskDesc, 'trainee' => $trainee, 'trainee_select' => $traineeSelect, 'duedate' => $duedate), array('id' => $id));
        return 'Task Updated';
    }

    public function delete_tasks($data){
        global $wpdb;
        $id = $data['id'];
        $wpdb->delete("{$wpdb->prefix}tasks", array('id' => $id));
        return 'Task Deleted';
    }

    public function get_tasks($data){
        global $wpdb;
        $id = $data['id'];
        $tasks = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tasks WHERE id = {$id}");
        return $tasks;
    }

    public function get_all_tasks(){
        global $wpdb;
        $tasks = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tasks");
        return $tasks;
    }
 }