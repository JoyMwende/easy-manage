<?php

/**
 * Template Name: Admin Dashboard
 */


?>


<?php
$dashboard = get_template_directory_uri() . "/assets/dashboard.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$usersDark = get_template_directory_uri() . "/assets/users-dark.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";


$search = get_template_directory_uri() . "/assets/search.png";
$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";


if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login');
}

$count_total_users = count_users();
$total_users = $count_total_users['total_users'];

global $wpdb;

$query = "
        SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' WHERE meta2.meta_value = 'Project Manager'
    ";

    $project_managers = $wpdb->get_results($query);

    $total_pm = count($project_managers);


// $project_manager = get_users( array( 'role' => 'project_manager' ) );


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
                <a href="/easy-manage/admin-dashboard" class="current-page">
                    <img src="<?php echo $dashboard ?>" alt="">
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
                    <h3>Welcome, admin</h3>
                    <p>Today is Saturday, 10 June 2023</p>
                </div>
                <div class="account">
                    <img src="<?php echo $account; ?>" alt="">
                    <div class="profile">
                        <h4>admin</h4>
                        <p>admin</p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>

        <div class="search-bar">
            <img src="<?php echo $search; ?>" alt="">
            <input type="search" name="search" id="search" placeholder="Search trainees, trainers, tasks etc.">
        </div>

        <div class="admin-content">
            <div class="admin-tasks-count-box">
                <div class="admin-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Users</p>
                        <h3><?php echo $total_users; ?> Users</h5>
                    </section>
                </div>
                <div class="admin-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Project Managers</p>
                        <h3><?php echo $total_pm; ?> Project Managers</h5>
                    </section>
                </div>
                <div class="admin-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Trainers</p>
                        <h3>10 Trainees</h5>
                    </section>
                </div>
                <div class="admin-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Trainees</p>
                        <h3>20 Trainees</h5>
                    </section>
                </div>
                <div class="admin-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $totalTasks; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Tasks Created</p>
                        <h3>50 Tasks</h5>
                    </section>
                </div>
            </div>
        </div>


        <div class="recent-tasks">
            <h3>Latest User Created</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // require_once('path/to/wp-load.php');

                    $users = get_users(
                    array(
                        'orderby' => 'user_registered',
                        'order' => 'DESC',
                        'number' => 5
                    )
                    );

                    foreach ($users as $user) {
                    $username = $user->user_login;
                    $email = $user->user_email;
                    $display_name = $user->display_name;
                    
                    // Retrieve user role from wp_usermeta table
                    $user_role = get_user_meta($user->ID, 'role');
                    $role = reset($user_role);

                    // Retrieve the creator's ID from user meta data
                    $creator_id = get_user_meta($user->ID, 'creator_id', true);

                    // Retrieve the role of the creator
                    $creator_role = '';
                    if (!empty($creator_id)) {
                        $creator_role = get_user_meta($creator_id, 'role', true);
                    }
                ?>
                    <tr>
                        <td><?php echo $display_name; ?></td>
                        <td><?php echo $email; ?></td>
                        <td><?php echo $role; ?></td>
                        <td><?php echo $creator_role; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>