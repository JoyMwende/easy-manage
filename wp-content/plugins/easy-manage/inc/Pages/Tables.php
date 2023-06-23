<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;

 class Tables{

    public function register(){
        $this->create_tasks();
        $this->create_cohorts();
    }
    public function create_tasks(){
        global $wpdb;
        $table = $wpdb->prefix . 'tasks';
        $task_data = "CREATE TABLE IF NOT EXISTS " . $table . " (
            id int(50) NOT NULL AUTO_INCREMENT,
            task_title text NOT NULL,
            task_desc text NOT NULL,
            trainee text NOT NULL,
            trainee_select text NOT NULL,
            duedate date NOT NULL,
            created_by text NOT NULL,
            is_deleted int NOT NULL DEFAULT 0,
            status text NOT NULL DEFAULT 'Not Started',
            PRIMARY KEY (id)
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($task_data);
    }
    public function create_cohorts(){
        global $wpdb;
        $table = $wpdb->prefix . 'cohorts';
        $cohort_data = "CREATE TABLE IF NOT EXISTS " . $table . " (
            id int(50) NOT NULL AUTO_INCREMENT,
            cohort_name text NOT NULL,
            location text NOT NULL,
            cohort_trainer text NOT NULL,
            languages text NOT NULL,
            startdate date NOT NULL,
            enddate date NOT NULL,
            is_deleted int NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($cohort_data);
    }

 }