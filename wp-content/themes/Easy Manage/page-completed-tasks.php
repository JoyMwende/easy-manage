<?php

/**
 * Template Name: Completed Tasks
 */

?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$assignDark = get_template_directory_uri() . "/assets/assigned-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";

if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login');
}

global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$completed_tasks = get_completed_tasks();


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
                <a href="/easy-manage/assigned-tasks" class="trainee-dash">
                    <img src="<?php echo $assignDark; ?>" alt="">
                    <p>Assigned Tasks</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/completed-tasks" class="current-page">
                    <img src="<?php echo $completed; ?>" alt="">
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
            <h3>Completed Tasks</h3>
            <table class="table table hover">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <?php if (empty($completed_tasks)) { ?>
            <div class="bg-light h-75 d-flex justify-content-center align-items-center">
                <h2 class="text-center">No Completed Tasks</h2>
            </div>
                    <?php 
                    } else {
                    foreach ($completed_tasks as $completed_task): ?>
                        <tr onclick="location.href='/easy-manage/trainee-single-task/';" style="cursor: pointer;">
                            <td>
                                <?php echo $completed_task->task_title; ?>
                            </td>
                            <td>
                                <?php echo $completed_task->task_desc; ?>
                            </td>
                            <td>
                                <?php echo $completed_task->duedate; ?>
                            </td>
                            <td class="tasksbtn">
                                <button type="submit">
                                    <?php if ($completed_task->status == 'Completed'): ?>
                                        <img src="<?php echo $completed; ?>" alt="">
                                        <?php echo $completed_task->status; ?>
                                    <?php else:
                                        echo "Error occurred"; ?>
                                    <?php endif; ?>
                                </button>
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