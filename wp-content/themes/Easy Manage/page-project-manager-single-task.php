<?php

/**
 * Template Name: Project Manager Single Task
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$traineesDark = get_template_directory_uri() . "/assets/trainees-dark.png";
$trainers = get_template_directory_uri() . "/assets/trainer.png";
$plus = get_template_directory_uri() . "/assets/add.png";
$edit = get_template_directory_uri() . "/assets/edit.png";
$delete = get_template_directory_uri() . "/assets/delete.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
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
                    <a href="/easy-manage/trainees" class="trainee-dash">
                        <img src="<?php echo $traineesDark; ?>" alt="">
                        <p>Trainees</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/trainers" class="current-page">
                        <img src="<?php echo $trainers; ?>" alt="">
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
        
        <div class="trainee-single-task">
            <div class="task-text">
                <h1>Tasks Title</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipiscing elit varius condimentum ultrices congue feugiat montes, 
                    velit fringilla nostra gravida neque proin quam lacinia ante taciti orci dis. Taciti luctus hac nulla ante 
                    proin suspendisse venenatis pretium integer, euismod sociis quam eu arcu est cursus augue, sociosqu sagittis 
                    sapien magna nascetur vitae commodo risus. Nascetur maecenas fames vel sollicitudin hac proin hendrerit dictum 
                    sed fringilla ridiculus penatibus, mattis eros varius litora euismod nullam ultrices est cum quam.
                </p>
            </div>
            <div class="duedate">
                <h6>Due Date: </h6>
                <p>15/06/2023</p>
            </div>
            <div class="status">
                <h6>Status</h6>
                <div class="tasksbtn">
                    <button type="submit">
                        <img src="<?php echo $progress; ?>" alt="">
                        In progress
                    </button>
                </div>
            </div>
            <div class="tasksmodifybtn">
                <a href="/easy-manage/update-task" style="text-decoration: none;"><button type="submit">
                    <img src="<?php echo $edit; ?>" alt="">
                    Edit
                </button></a>
                <button type="submit" style="background-color: #F3DEDE; color: #DF5656;">
                    <img src="<?php echo $delete; ?>" alt="">
                    Delete
                </button>
            </div>
        </div>

    </div>
</div>

<?php get_footer(); ?>