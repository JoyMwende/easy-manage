<?php

/**
 * Template Name: Project Manager Trainees
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$traniees = get_template_directory_uri() . "/assets/trainees.png";
$trainersDark = get_template_directory_uri() . "/assets/trainer-dark.png";
$plus = get_template_directory_uri() . "/assets/add.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$account = get_template_directory_uri() . "/assets/account.png";
$search = get_template_directory_uri() . "/assets/search.png";

if (isset($_POST['logout'])) {
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
                    <a href="/easy-manage/project-manager-dashboard" class="trainee-dash">
                        <img src="<?php echo $dashboardDark ?>" alt="">
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
                    <a href="/easy-manage/project-manager-trainees" class="current-page">
                        <img src="<?php echo $traniees; ?>" alt="">
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
                    <h5>Joy</h5>
                    <p>Project Manager</p>
                </div>
            </div>
        </div>
    </div>


    <div class="page-content">
        <div class="main-trainee-nav">
            <nav class="trainee-nav">
                <div class="trainee-welcome-text">
                    <h3>Welcome, Joy</h3>
                    <p>Today is Saturday, 10 June 2023</p>
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
            <img src="<?php echo $search; ?>" alt="">
            <input type="search" name="search" id="search" placeholder="Search trainees, trainers, tasks etc.">
        </div>

        <div class="assigned-tasks-table bg-light p-2">
            <h3>Trainees</h3>
            <table class="table table-light">
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