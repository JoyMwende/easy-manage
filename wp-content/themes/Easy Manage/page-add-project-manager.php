<?php

/**
 * Template Name: Add Project Manager
 */

get_header();

?>
<?php

$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$usersDark = get_template_directory_uri() . "/assets/users-dark.png";
$addNewProjectManager = get_template_directory_uri() . "/assets/add-user.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$account = get_template_directory_uri() . "/assets/account.png";


// if (is_user_logged_in()) {
// // wp_redirect('/easy-manage/admin-dashboard');
// exit;
// }

if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

$firstnameError = $lastnameError = $emailError = $roleError = $passwordError = '';

global $wpdb;
global $successmsg;
global $errormsg;
$successmsg = false;
$errormsg = false;

$user_logged_in = wp_get_current_user();
// $user_logged_data = $user_logged_in->user_login. ' ' . $user_logged_in->last_name;

if (isset($_POST['createpmbtn'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    // $created_by = $user_logged_data;

    if (empty($firstname)) {
        $firstnameError = "First Name is required!";
    }
    if (empty($lastname)) {
        $lastnameError = "Last Name is required!";
    }
    if (empty($email)) {
        $emailError = "Email is required!";
    }
    if (empty($role)) {
        $roleError = "Role is required!";
    }
    if (empty($password)) {
        $passwordError = "Password is required!";
    }

    if (empty($firstnameError) && empty($lastnameError) && empty($emailError) && empty($roleError) && empty($passwordError)) {

        $token = isset($GLOBALS['token']) ? $GLOBALS['token'] : '';

    
        if (!empty($token)) {
            $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/project-managers';

            $body = array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'role' => $role,
                'password' => $password,
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
?>
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
                    <img src='<?php echo $dashboardDark; ?>' alt="">
                    <p>Dashboard</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/admin-tasks-list" class="trainee-dash">
                    <img src='<?php echo $tasksListDark; ?>' alt="">
                    <p>Tasks List</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/users" class="trainee-dash">
                    <img src='<?php echo $usersDark; ?>' alt="">
                    <p>Users</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/add-project-manager" class="current-page">
                    <img src='<?php echo $addNewProjectManager; ?>' alt="">
                    <p>Add Project Manager</p>
                </a>
            </article>
            <div class="sidebar-line"></div>
            <article>
                <a href="#" class="trainee-dash">
                    <form action="" method="POST">
                        <div class="logoutform">
                            <img src='<?php echo $logoutDark; ?>' alt="">
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
                    <h3>Welcome,
                        <?php echo $user_logged_in->user_login ?>
                    </h3>
                    <p>
                        <?php
                        $current_date = date('l, j F Y');
                        echo "Today is " . $current_date ?>
                    </p>
                </div>
                <div class="account">
                    <img src='<?php echo $account; ?>' alt="">
                    <div class="profile">
                        <h4>
                            <?php echo $user_logged_in->user_login; ?>
                        </h4>
                        <p>
                            <?php echo $user_logged_in->user_login; ?>
                        </p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>

        <div class="container">
            <?php if ($successmsg): ?>
                <div class="alert alert-success" role="alert" id="successalert">
                    Project Manager created successfully!
                </div>
                <script>
                    document.getElementById("successalert").style.display = "flex";
                    setTimeout(function () {
                        document.getElementById("successalert").style.display = "none";
                    }, 3000);
                </script>';
            <?php endif;
            if ($errormsg): ?>
                <div class="alert alert-danger" role="alert" id="erroralert">
                    Project Manager not created! Please try again.
                </div>
                <script>
                    document.getElementById("erroralert").style.display = "flex";
                    setTimeout(function () {
                        document.getElementById("erroralert").style.display = "none";
                    }, 3000);
                </script>';
            <?php endif;
            ?>
            <div class="add-content shadow-sm d-flex flex-column bg-light p-4">
                <form action="" method="post">
                    <h2 class="text-center">Add New Project Manager</h2>
                    <p style="color: red;"><span class="error">* required field</span></p>
                    <div class="form-content">
                        <div class="add-field">
                            <label>First Name<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="text" name="firstname" id="firstname"
                                    placeholder="Project Manager first name">
                                <span class="error" style="color: red;">
                                    <?php if ($firstnameError) {
                                        echo $firstnameError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Last Name<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="text" name="lastname" id="lastname"
                                    placeholder="Project Manager last name">
                                <span class="error" style="color: red;">
                                    <?php if ($lastnameError) {
                                        echo $lastnameError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Email<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="email" name="email" id="email" placeholder="Project Manager email">
                                <span class="error" style="color: red;">
                                    <?php if ($emailError) {
                                        echo $emailError;
                                    } ?>
                                </span>
                            </div>
                            <div class="add-field">
                                <label>Role<span style="color: red;">*</span></label>
                                <div class="input">
                                    <input type="text" name="role" id="role" value="project_manager" readonly>
                                    <span class="error" style="color: red;">
                                        <?php if ($roleError) {
                                            echo $roleError;
                                        } ?>
                                    </span>
                                </div>
                            </div>
                            <div class="add-field">
                                <label>Password<span style="color: red;">*</span></label>
                                <div class="input">
                                    <input type="password" name="password" id="password"
                                        placeholder="Project Manager password">
                                    <span class="error" style="color: red;">
                                        <?php if ($passwordError) {
                                            echo $passwordError;
                                        } ?>
                                    </span>
                                </div>
                            </div>
                            <div class="add-btn-pm mt-2">
                                <button type="submit" name="createpmbtn">CREATE PROJECT MANAGER</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>