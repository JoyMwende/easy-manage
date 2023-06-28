<?php

/**
 * Template Name: Trainee Dashboard
 */


?>


<?php
$dashboard = get_template_directory_uri() . "/assets/dashboard.png";
$assignDark = get_template_directory_uri() . "/assets/assigned-dark.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$completedDark = get_template_directory_uri() . "/assets/completed-dark.png";
$logout = get_template_directory_uri() . "/assets/logout.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";

$new = get_template_directory_uri() . "/assets/new.png";
$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";

if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login/');
}

global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$total_assigned_tasks = count_total_assigned_tasks();

$total_started_tasks = count_total_started_tasks();

$total_completed_tasks = count_total_completed_tasks();

$total_group_tasks = count_total_group_tasks();

$newest_tasks = newest_tasks();

$recent_tasks = get_assigned_tasks();

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
                <a href="/easy-manage/trainee-dashboard" class="current-page">
                    <img src="<?php echo $dashboard ?>" alt="">
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
                        <h4><?php echo $user_logged_in->user_login; ?></h4>
                        <p><?php echo $user_role; ?></p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>

        <div class="trainee-content">
            <div class="tasks-count-box">
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $totalTasks; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Tasks</p>
                        <h3><?php echo $total_assigned_tasks; ?> Tasks</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Group Tasks</p>
                        <h3><?php echo $total_group_tasks; ?> Tasks</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $completed; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Completed Tasks</p>
                        <h3><?php echo $total_completed_tasks; ?> Tasks</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $progress; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Tasks in Progress</p>
                        <h3><?php echo $total_started_tasks; ?> Tasks</h5>
                    </section>
                </div>
            </div>

            <div class="tasks-analysis">
                <h3>Total Tasks</h3>
                <div class="big-circle">
                    <div class="small-circle">
                        <h5>10</h5>
                        <p>projects</p>
                    </div>
                </div>
                <div class="tasks-details">
                    <section class="taskscountcolor">
                        <div class="taskscolor1"></div>
                        <h6>Tasks in Progress</h6>
                    </section>
                    <section class="taskscountcolor">
                        <div class="taskscolor2"></div>
                        <h6>Completed Tasks</h6>
                    </section>
                </div>
            </div>

            <div class="newest-tasks">
                <div><h3>Newest Tasks</h3></div>
                <?php foreach($newest_tasks as $newest_task): ?>
                <div class="newest">
                    <img src="<?php echo $new; ?>" alt="">
                    <div class="newest-content">
                        <h5><?php echo $newest_task->task_title; ?></h5>
                        <p><?php echo $newest_task->task_desc; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="recent-tasks">
            <h3>Recent Tasks</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_tasks as $recent_task): ?>
                    <tr>
                        <td><?php echo $recent_task->task_title; ?></td>
                        <td><?php echo $recent_task->task_desc; ?></td>
                        <td><?php echo $recent_task->duedate; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>