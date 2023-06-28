<?php

/**
 * Template Name: Trainee Single Tasks
 */

?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$assignDark = get_template_directory_uri() . "/assets/assigned-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$completedDark = get_template_directory_uri() . "/assets/completed-dark.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$account = get_template_directory_uri() . "/assets/account.png";
$notstarted = get_template_directory_uri() . "/assets/not-started.png";

if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login');
}

global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$single_task = trainer_get_single_task();


?>

<?php get_header(); ?>

<div class="page">
    <div class="sidebar">
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
        <div class="trainee-single-task">
            <div class="task-text">
                <h1><?php echo $single_task->task_title; ?></h1>
                <p><?php echo $single_task->task_desc; ?></p>
            </div>
            <div class="duedate">
                <h6>Due Date: </h6>
                <p><?php echo $single_task->duedate; ?></p>
            </div>
            <div class="status">
                <h6>Status</h6>
                <div class="tasksbtn">
                    <button type="submit">
                        <img src="<?php echo $progress; ?>" alt="">
                        <?php echo $single_task->status; ?>
                    </button>
                </div>
            </div>
            <div class="taskscompletebtn">
                <button type="submit">
                    <img src="<?php echo $progress; ?>" alt="">
                    Mark as Complete
                </button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>