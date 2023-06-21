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

if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login');
}

global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = $wpdb->get_row("SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
    FROM {$wpdb->users} AS users
    LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
    LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE id = $user_logged_in->ID")


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
                        <p><?php echo $user_role->role; ?></p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>
        <div class="trainee-single-task">
            <div class="task-text">
                <h1>Tasks Title</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit varius condimentum ultrices congue feugiat montes, 
                    velit fringilla nostra gravida neque proin quam lacinia ante taciti orci dis. Taciti luctus hac nulla ante 
                    proin suspendisse venenatis pretium integer, euismod sociis quam eu arcu est cursus augue, sociosqu sagittis 
                    sapien magna nascetur vitae commodo risus. Nascetur maecenas fames vel sollicitudin hac proin hendrerit dictum 
                    sed fringilla ridiculus penatibus, mattis eros varius litora euismod nullam ultrices est cum quam.
                </p>
            </div>
            <div class="duedate">
                <h6>Due Date: </h6>
                <p>15/06/2023</p>
            </div>
            <div class="status">
                <h6>Status</h6>
                <div class="tasksbtn">
                    <button type="submit">
                        <img src="<?php echo $progress; ?>" alt="">
                        In progress
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