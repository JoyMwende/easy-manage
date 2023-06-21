<?php

/**
 * Template Name: Users
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$usersimg = get_template_directory_uri() . "/assets/users.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$activate = get_template_directory_uri() . "/assets/activate.png";
$deactivate = get_template_directory_uri() . "/assets/deactivate.png";

$search = get_template_directory_uri() . "/assets/search.png";
$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";

if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

$users_per_page = 5;
$current_page = isset($_GET['page']) ? absint($_GET['page']) : 1;
$offset = ($current_page - 1) * $users_per_page;

$args = array(
    'orderby' => 'user_registered',
    'order' => 'DESC',
    'number' => $users_per_page,
    'offset' => $offset,
);

$users_query = new WP_User_Query($args);
$users = $users_query->get_results();

$total_users = $users_query->get_total();
$total_pages = ceil($total_users / $users_per_page);


global $wpdb;

$query = "
        SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role'
    ";

$users = $wpdb->get_results($query);

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
                <a href="/easy-manage/users" class="current-page">
                    <img src="<?php echo $usersimg; ?>" alt="">
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
                    <p>Today is Saturday, 10 June 2023</p>
                </div>
                <div class="account">
                    <img src="<?php echo $account; ?>" alt="">
                    <div class="profile">
                        <h4><?php echo $user_logged_in->user_login; ?></h4>
                        <p><?php echo $user_logged_in->user_login; ?></p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>

        <div class="search-bar mb-1">
            <img src="<?php echo $search; ?>" alt="">
            <input type="search" name="search" id="search" placeholder="Search trainees, trainers, project managers etc.">
        </div>

        <div class="assigned-tasks-table">
            <h3>Trainers</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Second Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <?php
                    foreach ($users as $user) {
                    ?>
                        <tr>
                            <td><?php echo $user->firstname; ?></td>
                            <td><?php echo $user->lastname; ?></td>
                            <td><?php echo $user->email; ?></td>
                            <td><?php echo $user->role; ?></td>
                            <td class="tasksbtn">
                                <form action="" method="post">
                                    <button type="submit">
                                        <img src="<?php echo $activate; ?>" alt="">
                                        Activate
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            $paginate_args = array(
                'base' => add_query_arg('page', '%#%'),
                'format' => '',
                'total' => $total_pages,
                'current' => $current_page,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
                'type' => 'plain',
            );
            echo paginate_links($paginate_args);
            ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>