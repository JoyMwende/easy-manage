<?php

/**
 * Template Name: Sidebar
 */

 get_header();

 ?>

 <?php
 $dashboard = get_template_directory_uri() . "/assets/dashboard.png";
 $dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";

 $tasksList = get_template_directory_uri() . "/assets/tasks-list.png";
 $tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";

 $users = get_template_directory_uri() . "/assets/users.png";
 $usersDark = get_template_directory_uri() . "/assets/users-dark.png";

 $addTrainee = get_template_directory_uri() . "/assets/add-user.png";
 $addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";

 $logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
 $account = get_template_directory_uri() . "/assets/account.png";


 ?>

     <div class="sidebar">
        <div class="logo">
            <a href="#">
                <h3>Eazzy Manage</h3>
            </a>
        </div>
        <div class="sidebar-content">
            <article>
                <a href="/easy-manage/admin-dashboard" class="current-page">
                    <img src="<?php echo $dashboard; ?>" alt="">
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

 <?php get_footer(); ?>