<?php

/**
 * Template Name: Project Manager Dashboard
 */


?>


<?php
$dashboard = get_template_directory_uri() . "/assets/dashboard.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$traineesDark = get_template_directory_uri() . "/assets/trainees-dark.png";
$trainersDark = get_template_directory_uri() . "/assets/trainer-dark.png";
$plus = get_template_directory_uri() . "/assets/add.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";


$searchimg = get_template_directory_uri() . "/assets/search.png";
$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";


if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$total_trainers = count_total_trainers_pm();
$total_trainees = count_total_trainees_pm();
$total_tasks = count_total_tasks_pm();

$latest_created_tasks = fetch_latest_created_tasks_pm();

$table = $wpdb->prefix . 'users';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $users = $wpdb->get_results("SELECT * FROM $table WHERE username LIKE '%$search%'");
    $tasks = $wpdb->get_results("SELECT * FROM $table WHERE task_title LIKE '%$search%'");

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
        <div class="main-sidebar-content">
            <div class="sidebar-content">
                <article>
                    <a href="/easy-manage/project-manager-dashboard" class="current-page">
                        <img src="<?php echo $dashboard ?>" alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/project-manager-tasks-list" class="trainee-dash">
                        <img src="<?php echo $tasksListDark; ?>" alt="">
                        <p>Tasks List</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/project-manager-trainees" class="trainee-dash">
                        <img src="<?php echo $traineesDark; ?>" alt="">
                        <p>Trainees</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/trainers" class="trainee-dash">
                        <img src="<?php echo $trainersDark; ?>" alt="">
                        <p>Trainers</p>
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
                    <h5>
                        <?php echo $user_logged_in->user_login; ?>
                    </h5>
                    <p>
                        <?php echo $user_role; ?>
                    </p>
                </div>
            </div>
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
                <div class="btnadd">
                    <a href="/easy-manage/add-trainer"><button type="submit">
                            <img src="<?php echo $plus; ?>" alt="">
                            New Trainer
                        </button></a>
                </div>
            </nav>
        </div>

        <hr>

        <div class="search-bar">
            <form action="" method="get">
                <label for="search">
                    <img src="<?php echo $searchimg; ?>" alt="" onclick="performSearch()">
                </label>
                <input type="search" name="search" id="search" placeholder="Search trainees, trainers, tasks etc.">
            </form>
        </div>

        <script>
            function performSearch() {
                document.querySelector('form').submit();
            }
        </script>


        <div class="ms-2">
            <div class="new-tasks-count-box">
                <div class="new-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Trainers</p>
                        <h3>
                            <?php echo $total_trainers; ?> Trainers</h5>
                    </section>
                </div>
                <div class="new-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Trainees</p>
                        <h3>
                            <?php echo $total_trainees; ?> Trainees</h5>
                    </section>
                </div>
                <div class="new-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $totalTasks; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Tasks Created</p>
                        <h3>
                            <?php echo $total_tasks; ?> Tasks</h5>
                    </section>
                </div>
            </div>
        </div>


        <div class="recent-tasks">
            <h3>Latest Tasks Created</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latest_created_tasks as $task) { ?>
                        <tr>
                            <td>
                                <?php echo $task->task_title; ?>
                            </td>
                            <td>
                                <?php echo $task->task_desc; ?>
                            </td>
                            <td>
                                <?php echo $task->duedate; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>