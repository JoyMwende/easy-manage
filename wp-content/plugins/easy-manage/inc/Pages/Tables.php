<?php

/**
 * @package Easy Manage Plugin
 */

 namespace Inc\Pages;

 class Tables{
    public function create_tasks(){
        global $wpdb;
        $table = $wpdb->prefix . 'tasks';
        $task_data = "CREATE TABLE IF NOT EXISTS " . $table . " (
            id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            task-title text NOT NULL,
            task-desc text NOT NULL,
            trainee text NOT NULL,
            trainee-select text NOT NULL,
            duedate date NOT NULL,
            created_by text NOT NULL,
            is_deleted int DEFAULT 0
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($task_data);
    }

 }