<?php

/**
 * Template Name: Update Task
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$traineesDark = get_template_directory_uri() . "/assets/trainees-dark.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$plus = get_template_directory_uri(). "/assets/add.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$account = get_template_directory_uri() . "/assets/account.png";


if(isset($_POST['logout'])){
    wp_logout();
    wp_redirect('/easy-manage/login');
}

global $wpdb;

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
                    <a href="/easy-manage/trainees" class="trainee-dash">
                        <img src="<?php echo $traineesDark; ?>" alt="">
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
        <div class="container">
            <div class="add-content shadow-sm d-flex flex-column bg-light p-4">
                <form action="" method="post">
                    <h2 class="text-center">Update Task</h2>
                    <div class="form-content">
                        <div class="add-field">
                            <label>Task Title</label>
                            <div class="input">
                                <input type="text" name="task-title" id="task-title" placeholder="Input task title">
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Task Description</label>
                            <div class="input">
                                <textarea name="task-desc" id="" cols="62" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Task Assigned To</label>
                            <div class="input">
                                <input type="text" name="trainee" id="trainee" value="" readonly>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Assign to</label>
                            <div class="input">
                                <select>
                                    <option value="--Select--">--Select--</option>
                                    <option value="Victory">Victory</option>
                                    <option value="Gerald">Gerald</option>
                                </select>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Due Date</label>
                            <div class="input">
                                <input type="date" name="duedate" id="duedate" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                        <div class="add-btn mt-2">
                            <button type="submit" name="updatetaskbtn">UPDATE TASK</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>'
    </div>
</div>

<?php get_footer(); ?>