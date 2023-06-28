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
$searchimg = get_template_directory_uri() . "/assets/search.png";

if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}


global $wpdb;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$trainees = pm_get_trainees();

$table = $wpdb->prefix . 'users';

if (isset($_GET['search'])) {
    $trainees = pm_search_users();
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
            <form action="" method="get">
                <label for="search">
                    <img src="<?php echo $searchimg; ?>" alt="" onclick="performSearch()">
                </label>
                <input type="search" name="search" id="search" placeholder="Search any user">
            </form>
        </div>
        
        <script>
            function performSearch() {
                document.querySelector('form').submit();
            }
        </script>

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
                    <?php if(empty($trainees)){ ?>
                    <tr>
                        <td colspan="4" class="text-center">No Trainees Found</td>
                    </tr>
                <?php } else { foreach($trainees as $trainee): ?>
                    <tr>
                        <td><?php echo $trainee->firstname; ?></td>
                        <td><?php echo $trainee->lastname; ?></td>
                        <td><?php echo $trainee->email; ?></td>
                        <td><?php echo $trainee->cohort; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php } ?>
                </tbody>
            </table>
        </div>



    </div>
</div>

<?php get_footer(); ?>