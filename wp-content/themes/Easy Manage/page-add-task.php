<?php

/**
 * Template Name: Add New Task
 */


?>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$traineesDark = get_template_directory_uri() . "/assets/trainees-dark.png";
$trainersDark = get_template_directory_uri() . "/assets/trainer-dark.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$plus = get_template_directory_uri() . "/assets/add.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$account = get_template_directory_uri() . "/assets/account.png";

if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

$taskTitleError = $taskDescError = $traineeError = $dueDateError = '';

global $successmsg;
$successmsg = false;

global $errormsg;
$errormsg = false;

global $wpdb;


if (isset($_POST['createtaskbtn'])) {
    $task_title = $_POST['task_title'];
    $task_desc = $_POST['task_desc'];
    $trainee = $_POST['trainee'];
    $duedate = $_POST['duedate'];

    $trainee_string = implode(', ', $trainee);

    $trainee_array = $trainee;

    if (empty($_POST['task_title'])) {
        $taskTitleError = "Task title is required!";
    }

    if (empty($_POST['task_desc'])) {
        $taskDescError = "Task Description is required!";
    }

    if (empty($_POST['trainee'])) {
        $traineeError = "Trainee(s) Names is required!";
    }

    if (empty($_POST['duedate'])) {
        $dueDateError = "Due Date is required!";
    }

    $loggged_in_user = wp_get_current_user();

    if (empty($taskTitleError) && empty($taskDescError) && empty($traineeError) && empty($dueDateError)) {

        $token = isset($GLOBALS['token']) ? $GLOBALS['token'] : '';


        if (!empty($token)) {
            $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/tasks';

            $body = array(
                'task_title' => $task_title,
                'task_desc' => $task_desc,
                'trainee' => $trainee,
                'duedate' => $duedate,
            );

            $args = array(
                'method' => 'POST',
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ),
                'body' => json_encode($body),
            );

            $response = wp_remote_request($url, $args);

            if (is_wp_error($response)) {
                echo 'Error: ' . $response->get_error_message();
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                $response_body = wp_remote_retrieve_body($response);

                if ($response_code === 200) {
                    $successmsg = true;
                } else {
                    echo 'Error: ' . $response_body;
                    $errormsg = true;
                }
            }
        } else {
            echo 'Token is missing or invalid.';
        }


    }

}


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
                    <h5>
                        <?php echo $user_logged_in->user_login; ?>
                    </h5>
                    <p>
                        <?php echo $user_role->role; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="page-content">
        <div class="main-trainee-nav">
            <nav class="trainee-nav">
                <div class="trainee-welcome-text">
                    <h3>Welcome,
                        <?php echo $user_logged_in->user_login; ?>
                    </h3>
                    <p>
                        <?php
                        $current_date = date('l, j F Y');
                        echo "Today is " . $current_date;
                        ?>
                    </p>
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
            <!-- Display success message -->
            <?php if ($successmsg): ?>
                <div class="alert alert-success" role="alert" id="successalert">
                    Task Created successfully!
                </div>
                <script>
                    document.getElementById("successalert").style.display = "flex";
                    setTimeout(function () {
                        document.getElementById("successalert").style.display = "none";
                    }, 3000);
                </script>
            <?php endif; ?>

            <!-- Display error message -->
            <?php if ($errormsg): ?>
                <div class="alert alert-danger" role="alert" id="erroralert">
                    Task not created! Please try again.
                </div>
                <script>
                    document.getElementById("erroralert").style.display = "flex";
                    setTimeout(function () {
                        document.getElementById("erroralert").style.display = "none";
                    }, 3000);
                </script>
            <?php endif; ?>

            <div class="add-content shadow-sm d-flex flex-column bg-light p-4">
                <form action="" method="post">
                    <h2 class="text-center">Add New Task</h2>
                    <p style="color: red;"><span class="error">* required field</span></p>
                    <div class="form-content">
                        <div class="add-field form-group">
                            <label>Task Title<span style="color: red;">*</span></label>
                            <div class="input">
                                <input class="form-control" type="text" name="task_title" id="task_title"
                                    placeholder="Input task title">
                                <span class="error" style="color: red;">
                                    <?php if ($taskTitleError) {
                                        echo $taskTitleError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field form-group">
                            <label>Task Description<span style="color: red;">*</span></label>
                            <div class="input">
                                <textarea class="form-control" name="task_desc" cols="62" rows="4"></textarea>
                                <span class="error" style="color: red;">
                                    <?php if ($taskDescError) {
                                        echo $taskDescError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field form-group">
                            <label>Task Assigned To<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="text" name="trainee[]" id="trainee" value="" readonly>
                                <span class="error" style="color: red;">
                                    <?php if ($traineeError) {
                                        echo $traineeError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Assign to<span style="color: red;">*</span></label>
                            <div class="input form-group">
                                <select name="trainee-select[]" multiple class="form-control" style="height: 100px">
                                    <?php
                                    // Query trainees with the specified conditions
                                    $args = array(
                                        'role' => 'trainee',
                                        'meta_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'is_deleted',
                                                'value' => 0,
                                                'compare' => '=',
                                            ),
                                            array(
                                                'key' => 'is_active',
                                                'value' => 1,
                                                'compare' => '=',
                                            ),
                                        ),
                                    );
                                    $trainees = get_users($args);

                                    foreach ($trainees as $trainee):
                                        $names = $trainee->user_login . " " . $trainee->last_name;
                                        ?>
                                        <option>
                                            <?php echo $names; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="add-field form-group">
                            <label>Due Date<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="date" name="duedate" id="duedate" placeholder="dd/mm/yyyy">
                                <span class="error" style="color: red;">
                                    <?php if ($dueDateError) {
                                        echo $dueDateError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-btn mt-2">
                            <button type="submit" name="createtaskbtn">CREATE TASK</button>
                        </div>
                    </div>
                </form>

                <script>
                    let select = document.querySelector('select[name="trainee-select[]"]');
                    select.addEventListener('change', updateTraineeField);

                    function updateTraineeField() {
                        let selectedOptions = Array.from(this.selectedOptions);
                        let traineeField = document.querySelector('input[name="trainee[]"]');
                        traineeField.value = selectedOptions.map(option => option.value).join(', ');
                    }

                </script>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>