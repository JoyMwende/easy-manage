<?php

/**
 * Template Name: Trainees
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$traniees = get_template_directory_uri() . "/assets/trainees.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$plus = get_template_directory_uri() . "/assets/add.png";
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

global $wpdb;

$query = "
        SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role, meta3.meta_value AS cohort
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role' 
        LEFT JOIN {$wpdb->usermeta} AS meta3 ON meta3.user_id = users.ID AND meta3.meta_key = 'cohort' WHERE meta2.meta_value = 'Trainee' 
    ";

$trainees = $wpdb->get_results($query);

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
                    <a href="/easy-manage/trainer-dashboard" class="trainee-dash">
                        <img src="<?php echo $dashboardDark ?>" alt="">
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
                    <a href="/easy-manage/trainees" class="current-page">
                        <img src="<?php echo $traniees; ?>" alt="">
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
        <div class="search-bar">
            <img src="<?php echo $search; ?>" alt="">
            <input type="search" name="search" id="search" placeholder="Search Trainees">
        </div>
        <div class="assigned-tasks-table">
            <h3>Trainees</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Second Name</th>
                        <th>Email</th>
                        <th>Cohort</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <?php foreach($trainees as $trainee): ?>
                    <tr>
                        <td><?php echo $trainee->firstname; ?></td>
                        <td><?php echo $trainee->lastname; ?></td>
                        <td><?php echo $trainee->email; ?></td>
                        <td><?php echo $trainee->cohort; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>