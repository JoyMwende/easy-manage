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

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

//GET TOTAL USERS
$total_users = count_total_users();

//get total project managers
$total_pm = count_total_project_managers();

//get total trainers
$total_trainers = count_total_trainers();

//get total trainees
$total_trainees = count_total_trainees();

//get total tasks
$total_tasks = count_total_tasks();

?>

<?php
// $curr_user = wp_get_current_user();
// $email = $curr_user->user_email;
// $password = $curr_user->user_pass;
// $role = $curr_user->roles[0];

// // var_dump($email, $password, $role);

// if($curr_user != 'administrator'){
//     // echo "You are not authorized to view this page.";
//     wp_redirect('http://localhost/easy-manage/404.php');
//     exit;
// }
?>
<?php get_header();

// $token = $_COOKIE['token'];
// var_dump($token)
?>

<div class="page">
    <div class="sidebar">
        <?php //get_sidebar(); ?>
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
                        <h3><?php echo $total_trainers; ?> Trainers</h5>
                    </section>
                </div>
                <div class="admin-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $group; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Trainees</p>
                        <h3><?php echo $total_trainees; ?> Trainees</h5>
                    </section>
                </div>
                <div class="admin-tasks-count shadow-sm">
                    <section class="tasks-img">
                        <img src="<?php echo $totalTasks; ?>" alt="">
                    </section>
                    <section class="tasks-nums">
                        <p>Total Tasks Created</p>
                        <h3><?php echo $total_tasks; ?> Tasks</h5>
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
                    
                    $users_role = get_user_meta($user->ID, 'wp_capabilities', true);
                    $users_role = array_keys($users_role)[0];

                    // Retrieve creator from wp_usermeta table
                    $creator_email = get_user_meta($user->ID, 'created_by');
                    $creator = reset($creator_email);

                    // Retrieve the creator's ID from user meta data
                    // $creator_id = $user->ID;

                    // // Retrieve the role of the creator
                    // $creator_role = '';
                    // if (!empty($creator_id)) {
                    //     $creator_role = get_user_meta($creator_id, 'role', true);
                    // }
                ?>
                    <tr>
                        <td><?php echo $display_name; ?></td>
                        <td><?php echo $email; ?></td>
                        <td><?php echo $users_role; ?></td>
                        <td><?php echo $creator; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>