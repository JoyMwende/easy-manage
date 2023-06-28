<?php

/**
 * Template Name: Admin Tasks List
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksList = get_template_directory_uri() . "/assets/tasks-list.png";
$usersDark = get_template_directory_uri() . "/assets/users-dark.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";

$searchimg = get_template_directory_uri() . "/assets/search.png";
$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$notstarted = get_template_directory_uri() . "/assets/not-started.png";
$account = get_template_directory_uri() . "/assets/account.png";

if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];


$tasks = admin_get_tasks();

if (isset($_GET['search'])) {
    $tasks = admin_search_tasks();
}


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
                <a href="/easy-manage/admin-tasks-list" class="current-page">
                    <img src="<?php echo $tasksList; ?>" alt="">
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
                    <h3>Welcome,
                        <?php echo $user_logged_in->user_login; ?>
                    </h3>
                    <p>
                        <?php
                        $current_date = date('l, j F Y');
                        echo "Today is " . $current_date;
                        ?>
                    </p>
                </div>
                <div class="account">
                    <img src="<?php echo $account; ?>" alt="">
                    <div class="profile">
                        <h4>
                            <?php echo $user_logged_in->user_login; ?>
                        </h4>
                        <p>
                            <?php echo $user_role; ?>
                        </p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>

        <div class="search-bar">
            <form action="" method="get">
                <label for="search">
                    <img src="<?php echo $searchimg; ?>" alt="" onclick="performSearch()">
                </label>
                <input type="search" name="search" id="search" placeholder="Search tasks">
            </form>
        </div>
        
        <script>
            function performSearch() {
                document.querySelector('form').submit();
            }
        </script>

        <div class="assigned-tasks-table">
            <h3>All Tasks</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Task Assigned To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <?php if(empty($tasks)){ ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No tasks found</td>
                        </tr>
                    <?php } else { foreach ($tasks as $task): ?>
                        <tr onclick="location.href='/easy-manage/admin-single-task/?id=<?php echo $task->id; ?>';"
                            style="cursor: pointer;">
                            <td>
                                <?php echo $task->task_title; ?>
                            </td>
                            <td>
                                <?php echo $task->task_desc; ?>
                            </td>
                            <td>
                                <?php echo $task->trainee; ?>
                            </td>
                            <td>
                                <?php echo $task->duedate; ?>
                            </td>
                            <td class="tasksbtn"><button type="submit">
                                    <?php if ($task->status == 'Not Started'): ?>
                                        <img src="<?php echo $notstarted; ?>" alt="">
                                        <?php echo $task->status; ?>
                                    <?php elseif ($task->status == 'In Progress'): ?>
                                        <img src="<?php echo $progress; ?>" alt="">
                                        <?php echo $task->status; ?>
                                    <?php elseif ($task->status == 'Completed'): ?>
                                        <img src="<?php echo $completed; ?>" alt="">
                                            <?php echo $task->status; ?>
                                        <?php endif; ?>
                                </button></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>