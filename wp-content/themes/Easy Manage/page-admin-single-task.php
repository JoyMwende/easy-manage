<?php

/**
 * Template Name: Admin Single Task
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$usersDark = get_template_directory_uri() . "/assets/users-dark.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$edit = get_template_directory_uri() . "/assets/edit.png";
$delete = get_template_directory_uri() . "/assets/delete.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$notstarted = get_template_directory_uri() . "/assets/not-started.png";

$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$account = get_template_directory_uri() . "/assets/account.png";

if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login');
}
global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$task = admin_get_single_task();

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
                    <a href="/easy-manage/admin-dashboard" class="trainee-dash">
                        <img src="<?php echo $dashboardDark ?>" alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/admin-tasks-list" class="trainee-dash">
                        <img src="<?php echo $tasksListDark; ?>" alt="">
                        <p>Tasks List</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/users" class="trainee-dash">
                        <img src="<?php echo $usersDark; ?>" alt="">
                        <p>Users</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/add-project-manager" class="trainee-dash">
                        <img src="<?php echo $addTraineeDark; ?>" alt="">
                        <p>Add Project Manager</p>
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
                    <p><?php
                        $current_date = date('l, j F Y');
                        echo "Today is " . $current_date;
                    ?></p>
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

        <div class="trainee-single-task">
            <div class="task-text">
                <h1><?php echo $task->task_title; ?></h1>
                <p><?php echo $task->task_desc; ?></p>
            </div>
            <div class="duedate">
                <h6>Task Assigned To: </h6>
                <p><?php echo $task->trainee; ?>
                </p>
            </div>
            <div class="duedate">
                <h6>Due Date: </h6>
                <p><?php echo $task->duedate; ?></p>
            </div>
            <div class="status">
                <h6>Status</h6>
                <div class="tasksbtn">
                    <button type="submit">
                        <?php if ($task->status == 'Not Started'): ?>
                            <img src="<?php echo $notstarted; ?>" alt="">
                            <?php echo $task->status; ?>
                        <?php elseif ($task->status == 'In Progress'): ?>
                            <img src="<?php echo $progress; ?>" alt="">
                            <?php echo $task->status; ?>
                        <?php elseif ($task->status == 'Completed'): ?>
                            <img src="<?php echo $completed; ?>" alt="">
                                <?php echo $task->status; ?> <?php endif; ?>
                    </button>
                </div>
            </div>
            <div class="tasksmodifybtn">
                <a href="/easy-manage/update-task" style="text-decoration: none;"><button type="submit">
                    <img src="<?php echo $edit; ?>" alt="">
                    Edit
                </button></a>
                <button type="submit" style="background-color: #F3DEDE; color: #DF5656;">
                    <img src="<?php echo $delete; ?>" alt="">
                    Delete
                </button>
            </div>
        </div>
        
    </div>
</div>

<?php get_footer(); ?>