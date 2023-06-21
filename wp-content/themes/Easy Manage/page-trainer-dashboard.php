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
                        <p><?php echo $user_role->role; ?></p>
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
                        <h3>20 Trainees</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $totalTasks; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Tasks</p>
                        <h3>30 Tasks</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $completed; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Submitted Tasks</p>
                        <h3>21 Tasks</h5>
                    </section>
                </div>
                <div class="tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $progress; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Tasks in Progress</p>
                        <h3>9 Tasks</h5>
                    </section>
                </div>
            </div>

            <div class="tasks-analysis">
                <h3>Total Tasks</h3>
                <div class="big-circle">
                    <div class="small-circle">
                        <h5>30</h5>
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
                        <h6>Submitted Tasks</h6>
                    </section>
                </div>
            </div>

            <div class="newest-tasks">
                <div>
                    <h3>Latest Submitted Tasks</h3>
                </div>
                <div class="newest">
                    <img src="<?php echo $completedDark; ?>" alt="">
                    <div class="newest-content">
                        <h5>Task Name</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    </div>
                </div>
                <div class="newest">
                    <img src="<?php echo $completedDark; ?>" alt="">
                    <div class="newest-content">
                        <h5>Task Name</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    </div>
                </div>
                <div class="newest">
                    <img src="<?php echo $completedDark; ?>" alt="">
                    <div class="newwest-content">
                        <h5>Task Name</h5>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    </div>
                </div>
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
                    <tr>
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
                        <td>15/06/2023</td>
                    </tr>
                    <tr>
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
                        <td>15/06/2023</td>
                    </tr>
                    <tr>
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
                        <td>15/06/2023</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>