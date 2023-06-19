<?php

/**
 * Template Name: Assigned Tasks
 */

?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$assign = get_template_directory_uri() . "/assets/assigned.png";
$completedDark = get_template_directory_uri() . "/assets/completed-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$completed = get_template_directory_uri() . "/assets/completed.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";


if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login/');
}

?>

<?php get_header(); ?>

<div class="page">
    <div class="sidebar" style="height: 92vh">
        <div class="logo">
            <a href="#">
                <h3>Eazzy Manage</h3>
            </a>
        </div>
        <div class="sidebar-content">
            <article>
                <a href="/easy-manage/trainee-dashboard" class="trainee-dash">
                    <img src="<?php echo $dashboardDark ?>" alt="">
                    <p>Dashboard</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/assigned-tasks" class="current-page">
                    <img src="<?php echo $assign; ?>" alt="">
                    <p>Assigned Tasks</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/completed-tasks" class="trainee-dash">
                    <img src="<?php echo $completedDark; ?>" alt="">
                    <p>Completed Tasks</p>
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
                    <h3>Welcome, Victory</h3>
                    <p>Here is what you've been doing</p>
                </div>
                <div class="account">
                    <img src="<?php echo $account; ?>" alt="">
                    <div class="profile">
                        <h3>Victory</h3>
                        <p>Trainee</p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>
        <div class="assigned-tasks-table">
            <h3>Assigned Tasks</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <tr onclick="location.href='/easy-manage/trainee-single-task/';" style="cursor: pointer;">
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
                        <td>15/06/2023</td>
                        <td class="tasksbtn">
                            <button type="submit">
                                <img src="<?php echo $completed; ?>" alt="">
                                Start
                            </button>
                        </td>
                    </tr>

                    <tr onclick="location.href='/easy-manage/trainee-single-task/';" style="cursor: pointer;">
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
                        <td>15/06/2023</td>
                        <td class="tasksbtn"><button type="submit">
                                <img src="<?php echo $progress; ?>" alt="">
                                In progress
                            </button></td>
                    </tr>
                    <tr onclick="location.href='/easy-manage/trainee-single-task/';" style="cursor: pointer;">
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit</td>
                        <td>15/06/2023</td>
                        <td class="tasksbtn"><button type="submit">
                                <img src="<?php echo $completed; ?>" alt="">
                                Start
                            </button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php get_footer(); ?>