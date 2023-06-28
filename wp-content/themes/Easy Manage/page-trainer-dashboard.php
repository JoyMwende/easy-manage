<?php

/**
 * Template Name: Trainer Dashboard
 */


?>


<?php
$dashboard = get_template_directory_uri() . "/assets/dashboard.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$traineesDark = get_template_directory_uri() . "/assets/trainees-dark.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$plus = get_template_directory_uri(). "/assets/add.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$completedDark = get_template_directory_uri() . "/assets/completed-dark.png";

$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
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

$total_trainees = count_total_trainees_trainer();

$total_tasks = count_total_tasks_trainer();

$total_submitted_tasks = count_total_submitted_tasks();

$total_tasks_in_progress = count_total_tasks_in_progress();

$latest_created_tasks = fetch_latest_created_tasks();

$latest_submitted_tasks = fetch_latest_submitted_tasks();

?>
<?php get_header(); ?>

<div class="page">
    <div class="sidebar">
        <div class="logo">
            <a href="#">
                <h3>Eazzy Manage</h3>
            </a>
        </div>
        <div class="main-sidebar-content">
            <div class="sidebar-content">
                <article>
                    <a href="/easy-manage/trainer-dashboard" class="current-page">
                        <img src="<?php echo $dashboard ?>" alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/tasks-list" class="trainee-dash">
                        <img src="<?php echo $tasksListDark; ?>" alt="">
                        <p>Tasks List</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/trainees" class="trainee-dash">
                        <img src="<?php echo $traineesDark; ?>" alt="">
                        <p>Trainees</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/add-trainee" class="trainee-dash">
                        <img src="<?php echo $addTraineeDark; ?>" alt="">
                        <p>Add Trainee</p>
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
            <div class="account-new">
                    <img src="<?php echo $account; ?>" alt="">
                    <div class="profile">
                        <h5><?php echo $user_logged_in->user_login; ?></h5>
                        <p><?php echo $user_role; ?></p>
                    </div>
            </div>
        </div>
    </div>


    <div class="page-content">
        <div class="main-trainee-nav">
            <nav class="trainee-nav">
                <div class="trainee-welcome-text">
                    <h3>Welcome, <?php echo $user_logged_in->user_login; ?></h3>
                    <p><?php
                        $current_date = date('l, j F Y');
                        echo "Today is " . $current_date;
                    ?></p>
                </div>
                <div class="btnadd">
                    <a href="/easy-manage/add-task"><button type="submit">
                        <img src="<?php echo $plus; ?>" alt="">
                        New Project
                    </button></a>
                </div>
            </nav>
        </div>

        <hr>

        <div class="trainee-content">
            <div class="tasks-count-box">
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Trainees</p>
                        <h3><?php echo $total_trainees; ?> Trainees</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $totalTasks; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Tasks</p>
                        <h3><?php echo $total_tasks; ?> Tasks</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $completed; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Submitted Tasks</p>
                        <h3><?php echo $total_submitted_tasks; ?> Tasks</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $progress; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Tasks in Progress</p>
                        <h3><?php echo $total_tasks_in_progress; ?> Tasks</h5>
                    </section>
                </div>
            </div>

            <div class="tasks-analysis">
                <h3>Total Tasks</h3>
                <div class="big-circle">
                    <div class="small-circle">
                        <h5><?php echo $total_tasks; ?></h5>
                        <p>Tasks</p>
                    </div>
                </div>
                <div class="tasks-details">
                    <section class="taskscountcolor">
                        <div class="taskscolor1"></div>
                        <h6>Tasks in Progress</h6>
                    </section>
                    <section class="taskscountcolor">
                        <div class="taskscolor2"></div>
                        <h6>Submitted Tasks</h6>
                    </section>
                </div>
            </div>

            <div class="newest-tasks">
                <div>
                    <h3>Latest Submitted Tasks</h3>
                </div>
                <?php foreach($latest_submitted_tasks as $latest_submitted_task){ ?>
                <div class="newest">
                    <img src="<?php echo $completedDark; ?>" alt="">
                    <div class="newest-content">
                        <h5><?php echo $latest_submitted_task->task_title; ?></h5>
                        <p><?php echo $latest_submitted_task->task_desc; ?></p>
                    </div>
                </div>
                <?php } ?>
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
                    <?php foreach($latest_created_tasks as $task){ ?>
                    <tr>
                        <td><?php echo $task->task_title; ?></td>
                        <td><?php echo $task->task_desc; ?></td>
                        <td><?php echo $task->duedate; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>