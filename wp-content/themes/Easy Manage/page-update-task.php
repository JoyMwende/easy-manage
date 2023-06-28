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
$plus = get_template_directory_uri() . "/assets/add.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$account = get_template_directory_uri() . "/assets/account.png";


if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

global $wpdb;
global $successmsg;
global $errormsg;

$successmsg = false;
$errormsg = false;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

$task = trainer_get_single_task();

if (isset($_POST['updatetaskbtn'])) {
    $task_title = $_POST['task_title'];
    $task_desc = $_POST['task_desc'];
    $trainee = $_POST['trainee'];
    $duedate = $_POST['duedate'];

    $task_id = $_GET['id'];

    $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';

    if (!empty($token)) {
        $url = "http://localhost/easy-manage/wp-json/easymanage/v4/tasks/" . $task_id;

        $body = array(
            'task_title' => $task_title,
            'task_desc' => $task_desc,
            'trainee' => $trainee,
            'duedate' => $duedate
        );

        $args = array(
            'method' => 'PUT',
            'headers' => array(
                'Authorization' => 'Bearer' . $token,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($body)
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
                        <?php echo $user_role; ?>
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
                    Task Updated successfully!
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
                    Task not updated! Please try again.
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
                    <h2 class="text-center">Update Task</h2>
                    <div class="form-content">
                        <div class="add-field form-group">
                            <label>Task Title</label>
                            <div class="input">
                                <input class="form-control" type="text" name="task_title" id="task_title"
                                    placeholder="Input task title" value="<?php echo $task->task_title; ?>">
                            </div>
                        </div>
                        <div class="add-field form-group">
                            <label>Task Description</label>
                            <div class="input">
                                <input class="form-control" name="task_desc" style="height: 15vh;"
                                    value="<?php echo $task->task_desc; ?>">
                            </div>
                        </div>
                        <div class="add-field form-group">
                            <label>Task Assigned To</label>
                            <div class="input">
                                <input type="text" name="trainee[]" id="trainee" value="<?php echo $task->trainee; ?>"
                                    readonly>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Assign to</label>
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
                            <label>Due Date</label>
                            <div class="input">
                                <input type="date" name="duedate" id="duedate" placeholder="dd/mm/yyyy"
                                    value="<?php echo $task->duedate; ?>">
                            </div>
                        </div>
                        <div class="add-btn mt-2">
                            <button type="submit" name="updatetaskbtn">UPDATE TASK</button>
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
        </div>'
    </div>
</div>

<?php get_footer(); ?>