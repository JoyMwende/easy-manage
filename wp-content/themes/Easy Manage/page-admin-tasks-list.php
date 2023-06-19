<?php

/**
 * Template Name: Admin Tasks List
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksList = get_template_directory_uri() . "/assets/tasks-list.png";
$usersDark = get_template_directory_uri() . "/assets/users-dark.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";

$search = get_template_directory_uri() . "/assets/search.png";
$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$notstarted = get_template_directory_uri() . "/assets/not-started.png";
$account = get_template_directory_uri() . "/assets/account.png";

if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login');
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
        <div class="sidebar-content">
        <article>
                    <a href="/easy-manage/admin-dashboard" class="trainee-dash">
                        <img src="<?php echo $dashboardDark ?>" alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/admin-tasks-list" class="current-page">
                        <img src="<?php echo $tasksList; ?>" alt="">
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

        <div class="assigned-tasks-table">
            <h3>All Tasks</h3>
            <table class="table table-hover">
            <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Task Assigned To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <tr onclick="location.href='/easy-manage/trainer-single-task/';" style="cursor: pointer;">
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing...</td>
                        <td>Joy</td>
                        <td>15/06/2023</td>
                        <td class="tasksbtn"><button type="submit">
                                <img src="<?php echo $notstarted; ?>" alt="">
                                Start
                            </button></td>
                    </tr>
                    </a>
                    <tr onclick="location.href='/easy-manage/trainer-single-task/';" style="cursor: pointer;">
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing...</td>
                        <td>Joy, Janice</td>
                        <td>15/06/2023</td>
                        <td class="tasksbtn"><button type="submit">
                                <img src="<?php echo $progress; ?>" alt="">
                                In progress
                            </button></td>
                    </tr>
                    <tr onclick="location.href='/easy-manage/trainer-single-task/';" style="cursor: pointer;">
                        <td>Sample Title</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing...</td>
                        <td>Victory</td>
                        <td>15/06/2023</td>
                        <td class="tasksbtn"><button type="submit">
                                <img src="<?php echo $notstarted; ?>" alt="">
                                Start
                            </button></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>