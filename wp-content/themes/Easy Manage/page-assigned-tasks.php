<?php

/**
 * Template Name: Assigned Tasks
 */

?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$assign = get_template_directory_uri() . "/assets/assigned.png";
$completedDark = get_template_directory_uri() . "/assets/completed-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";
$notstarted = get_template_directory_uri() . "/assets/not-started.png";


if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login/');
}

global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$assigned_tasks = get_assigned_tasks();

if(isset($_POST['markstartedbtn'])){
    $token = $_COOKIE['token'];
    $task_id = $_POST['id'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/assignedtasks/' . $task_id . '/markstarted';
    $args = array(
        'method' => 'PUT',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        echo 'User deactivated successfully';
        $assigned_tasks = get_assigned_tasks();
    } else {
        echo 'Error deactivating user: ' . $response_code;
    }
}

if(isset($_POST['markcompletebtn'])){
    $token = $_COOKIE['token'];
    $task_id = $_POST['id'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/assignedtasks/' . $task_id . '/markcomplete';
    $args = array(
        'method' => 'PUT',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        echo 'User deactivated successfully';
        $assigned_tasks = get_assigned_tasks();
    } else {
        echo 'Error deactivating user: ' . $response_code;
    }
}

?>

<?php get_header(); ?>

<div class="page">
    <div class="sidebar" style="height: 92vh">
        <div class="logo">
            <a href="#">
                <h3>Eazzy Manage</h3>
            </a>
        </div>
        <div class="sidebar-content">
            <article>
                <a href="/easy-manage/trainee-dashboard" class="trainee-dash">
                    <img src="<?php echo $dashboardDark ?>" alt="">
                    <p>Dashboard</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/assigned-tasks" class="current-page">
                    <img src="<?php echo $assign; ?>" alt="">
                    <p>Assigned Tasks</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/completed-tasks" class="trainee-dash">
                    <img src="<?php echo $completedDark; ?>" alt="">
                    <p>Completed Tasks</p>
                </a>
            </article>
            <div class="sidebar-line"></div>
            <article>
                <a href="#" class="trainee-dash">
                <form action="" method="POST">
                            <div class="logoutform">
                                <img src="<?php echo $logoutDark; ?>" alt="">
                                <input class="logout" name="logout" type="submit" value="Logout">
                            </div>
                        </form>
                </a>
            </article>
        </div>
    </div>

    <div class="page-content">
        <div class="main-trainee-nav">
            <nav class="trainee-nav">
                <div class="trainee-welcome-text">
                    <h3>Welcome, <?php echo $user_logged_in->user_login; ?></h3>
                    <p>Here is what you've been doing</p>
                </div>
                <div class="account">
                    <img src="<?php echo $account; ?>" alt="">
                    <div class="profile">
                        <h3><?php echo $user_logged_in->user_login; ?></h3>
                        <p><?php echo $user_role; ?></p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>
        <div class="assigned-tasks-table">
            <h3>Assigned Tasks</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <?php if(empty($assigned_tasks)){ ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No tasks assigned yet</td>
                        </tr>
                    <?php } else { foreach ($assigned_tasks as $assigned_task): ?>
                    <tr onclick="location.href='/easy-manage/trainee-single-task/';" style="cursor: pointer;">
                        <td><?php echo $assigned_task->task_title; ?></td>
                        <td><?php echo $assigned_task->task_desc; ?></td>
                        <td><?php echo $assigned_task->duedate; ?></td>
                        <td class="tasksbtn">
                            <form action="" method="post">
                                    <input type="hidden" name="id" value="<?php echo $assigned_task->id; ?>">
                                    <?php if ($assigned_task->status == 'Not Started') { ?>
                                        <button type="submit" name="markstartedbtn">
                                            <img src="<?php echo $notstarted; ?>" alt="">
                                            <?php echo $assigned_task->status; ?>
                                        </button>
                                    <?php } else if ($assigned_task->status == 'In Progress') { ?>
                                            <button type="submit" name="markcompletebtn">
                                                <img src="<?php echo $progress; ?>" alt="">
                                                <?php echo $assigned_task->status; ?>
                                            </button>
                                        <?php } else {
                                        echo "Invalid status";
                                    } ?>
                                </form>
                                </td>
                            </tr>
                    <?php endforeach; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php get_footer(); ?>