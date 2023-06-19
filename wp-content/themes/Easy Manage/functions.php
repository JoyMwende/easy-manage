<?php

function easymanagetheme_script_enqueue()
{
    // introduce bootstrap
    wp_register_style('bootstrapstyling', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', [], '5.2.3', 'all');
    wp_enqueue_style('bootstrapstyling');

    wp_register_script('jsbootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js', [], '5.2.3', false);
    wp_enqueue_script('jsbootstrap');

    wp_enqueue_style('style', get_template_directory_uri() . '/style.css', [], '1.0', 'all');
    wp_enqueue_script('main-script', get_template_directory_uri() . '/script.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'easymanagetheme_script_enqueue');

//add menu

function easymanagetheme_theme_setup()
{
    add_theme_support('menus');
    register_nav_menu('primary', 'Primary Header Navigation');
    register_nav_menu('secondary', 'Footer Navigation');
}
add_action('init', 'easymanagetheme_theme_setup');

global $successmsg;
$successmsg;
global $errormsg;
$errormsg;
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    return $data;
}


function validate_login_form()
{
    $emailError = $passwordError = '';
    $email = '';
    $password = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['email'])) {
            $emailError = "Email is required!";
        } else {
            $email = test_input($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format!";
            }
        }

        if (empty($_POST['password'])) {
            $passwordError = "Password is required!";
        } else {
            $password = test_input($_POST['password']);
        }

        // Perform additional validation or processing as needed
        if (empty($emailError) && empty($passwordError)) {
            // Form is valid, perform further processing
            // For example, authenticate user or redirect to a specific page
            wp_redirect('/easy-manage/trainee-dashboard');
        }
    }

    if (isset($_POST['loginbtn'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = wp_authenticate($email, $password);

        if (is_wp_error($user)) {
            echo $user->get_error_message();
        } else {
            if ($user->user_login == 'admin') {
                wp_set_current_user($user->ID, $user->user_login);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login);
                wp_redirect('/easy-manage/admin-dashboard');
            } else if ($user->user_login == 'Project Manager') {
                wp_set_current_user($user->ID, $user->user_login);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login);
                wp_redirect('/easy-manage/project-manager-dashboard');
            } else if ($user->user_login == 'Trainer') {
                wp_set_current_user($user->ID, $user->user_login);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login);
                wp_redirect('/easy-manage/trainer-dashboard');
            } else if ($user->user_login == 'Trainee') {
                wp_set_current_user($user->ID, $user->user_login);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login);
                wp_redirect('/easy-manage/trainee-dashboard');
            } else {
                echo 'Invalid login credentials';
            }
        }
    }

    // Display the login form with validation errors
    $login = '';
    $login .= '<div class="login-container">';
    $login .= '<div class="login-content shadow-sm d-flex flex-column bg-light p-4">';
    $login .= '<form action="' . esc_url($_SERVER["REQUEST_URI"]) . '" method="post">';
    $login .= '<h2 class="text-center">Login</h2>';
    $login .= '<p style="color: red;"><span class="error">* required field</span></p>';
    $login .= '<div class="form-content">
                    <div class="login-field">
                        <label>Email<span style="color: red;">*</span></label>
                        <div class="input">
                            <input type="email" name="email" id="email" placeholder="Enter your email" value="' . esc_attr($email) . '">
                            <span class="error" style="color: red;">' . esc_html($emailError) . '</span>
                        </div>
                    </div>
                    <div class="login-field">
                        <label>Password<span style="color: red;">*</span></label>
                        <div class="input">
                            <input type="password" name="password" id="password" placeholder="Enter your password">
                            <span class="error" style="color: red;">' . esc_html($passwordError) . '</span>
                        </div>
                    </div>';
    $login .= '<div class="login-btn mt-2">
                        <button type="submit" name="loginbtn">LOGIN</button>
                    </div>
                </div>';
    $login .= '</form>';
    $login .= '</div>';
    $login .= '</div>';

    echo $login;
}
add_shortcode('login', 'validate_login_form');



function addTrainee($attrs)
{
    $dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
    $tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
    $traineesDark = get_template_directory_uri() . "/assets/trainees-dark.png";
    $addNewTrainee = get_template_directory_uri() . "/assets/add-user.png";
    $plus = get_template_directory_uri() . "/assets/add.png";
    $logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";

    $account = get_template_directory_uri() . "/assets/account.png";

    if (isset($_POST['logout'])) {
        wp_logout();
        wp_redirect('/easy-manage/login');
    }

    $firstnameError = $lastnameError = $emailError = $cohortError = $roleError = $passwordError = '';
    $firstname = '';
    $lastname = '';
    $email = '';
    $cohort = '';
    $password = '';

    global $successmsg;
    global $errormsg;
    $successmsg = false;
    $errormsg = false;


    if (isset($_POST['createtraineebtn'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $cohort = $_POST['cohort'];

        if (empty($firstname)) {
            $firstnameError = "First Name is required!";
        } else {
            $firstname = test_input($_POST['password']);
        }

        if (empty($lastname)) {
            $lastnameError = "Last Name is required!";
        } else {
            $lastname = test_input($_POST['password']);
        }

        if (empty($email)) {
            $emailError = "Email is required!";
        } else {
            $email = test_input($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format!";
            }
        }

        if (empty($cohort)) {
            $cohortError = "Cohort is required!";
        } else {
            $cohort = test_input($_POST['cohort']);
        }

        if (empty($role)) {
            $roleError = "Role is required!";
        } else {
            $role = test_input($_POST['role']);
        }

        if (empty($password)) {
            $passwordError = "Password is required!";
        } else {
            $password = test_input($_POST['password']);
        }

        $user_id = wp_create_user($firstname, $password, $email);

        if (!is_wp_error($user_id)) {
            update_user_meta($user_id, 'last_name', $lastname);
            update_user_meta($user_id, 'role', $role);
            update_user_meta($user_id, 'cohort', $cohort);

            $user = wp_signon([
                'user_login' => $email,
                'user_password' => $password,
                'remember' => true
            ]);

            if (!is_wp_error($user)) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login, $user);

                $successmsg = true;
                // wp_redirect('easy-manage/users');
                exit;
            } else {
                $errormsg = true;
                $err = "Invalid email or password";
        } 
        }
        // if (empty($emailError) && empty($passwordError)) {
        //     wp_redirect('/easy-manage/trainee-dashboard');
        // }
    }

    $addTrainee = '';
    $addTrainee .= '<div class="page">
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
                                            <img src=' . $dashboardDark . ' alt="">
                                            <p>Dashboard</p>
                                        </a>
                                    </article>
                                    <article>
                                        <a href="/easy-manage/tasks-list" class="trainee-dash">
                                            <img src=' . $tasksListDark . ' alt="">
                                            <p>Tasks List</p>
                                        </a>
                                    </article>
                                    <article>
                                        <a href="/easy-manage/trainees" class="trainee-dash">
                                            <img src=' . $traineesDark . ' alt="">
                                            <p>Trainees</p>
                                        </a>
                                    </article>
                                    <article>
                                        <a href="/easy-manage/add-trainee" class="current-page">
                                            <img src=' . $addNewTrainee . ' alt="">
                                            <p>Add Trainee</p>
                                        </a>
                                    </article>
                                    <div class="sidebar-line"></div>
                                    <article>
                                        <a href="#" class="trainee-dash">
                                            <form action="" method="POST">
                                                <div class="logoutform">
                                                    <img src=' . $logoutDark . ' alt="">
                                                    <input class="logout" name="logout" type="submit" value="Logout">
                                                </div>
                                            </form>
                                        </a>
                                    </article>
                                </div>
                                <div class="account-new">
                                        <img src=' . $account . ' alt="">
                                        <div class="profile">
                                            <h5>Janice</h5>
                                            <p>Trainer</p>
                                        </div>
                                </div>
                            </div>
                        </div>


                        <div class="page-content">
                            <div class="main-trainee-nav">
                                <nav class="trainee-nav">
                                    <div class="trainee-welcome-text">
                                        <h3>Welcome, Janice</h3>
                                        <p>Today is Saturday, 10 June 2023</p>
                                    </div>
                                    <div class="btnadd">
                                        <a href="/easy-manage/add-task/"><button type="submit">
                                            <img src=' . $plus . ' alt="">
                                            New Project
                                        </button></a>
                                    </div>
                                </nav>
                            </div>

                            <hr>';
    $addTrainee .= '<div class="container">';
    echo '<div class="alert alert-success" role="alert" id="success">
                New project created successfully
             </div>';

    echo '<script> document.getElementById("success").style.display = "none"; </script>';

    if ($successmsg == true) {
        echo '<script> document.getElementById("success").style.display = "flex"; </script>';

        echo    '<script> 
                        setTimeout(function(){
                            document.getElementById("success").style.display ="none";
                        }, 3000);
                    </script>';
    }

    echo '<div class="alert alert-danger" role="alert" id="error">
    An error occurred while creating a new project. Please try again!
  </div>';

echo '<script> document.getElementById("error").style.display = "none"; </script>';

if ($errormsg == true) {
echo '<script> document.getElementById("error").style.display = "flex"; </script>';

echo    '<script> 
             setTimeout(function(){
                 document.getElementById("error").style.display ="none";
             }, 3000);
         </script>';
}
    $addTrainee .= '<div class="add-content shadow-sm d-flex flex-column bg-light p-4">';
    $addTrainee .= '<form action="' . esc_url($_SERVER["REQUEST_URI"]) . '" method="post">';
    $addTrainee .= '<h2 class="text-center">Add New Trainee</h2>';
    $addTrainee .= '<p style="color: red;"><span class="error">* required field</span></p>';
    $addTrainee .= '<div class="form-content">
                    <div class="add-field">
                        <label>First Name<span style="color: red;">*</span></label>
                        <div class="input">
                            <input type="text" name="firstname" id="firstname" placeholder="Trainee first name">
                            ' . ($firstnameError ? '<span class="error" style="color: red;">' . $firstnameError . '</span>' : '') . '
                        </div>
                    </div>
                    <div class="add-field">
                        <label>Last Name<span style="color: red;">*</span></label>
                        <div class="input">
                            <input type="taxt" name="lastname" id="lastname" placeholder="Trainee last name">
                            ' . ($lastnameError ? '<span class="error" style="color: red;">' . $lastnameError . '</span>' : '') . '
                        </div>
                    </div>
                    <div class="add-field">
                        <label>Email<span style="color: red;">*</span></label>
                        <div class="input">
                            <input type="email" name="email" id="email" placeholder="Trainee email">
                            ' . ($emailError ? '<span class="error" style="color: red;">' . $emailError . '</span>' : '') . '
                        </div>
                    </div>
                    <div class="add-field">
                        <label>Cohort<span style="color: red;">*</span></label>
                        <div class="input">
                            <select name="cohort">
                                <option value="WordPress">WordPress</option>
                                <option value="Angular">Angular</option>
                            </select>
                            ' . ($cohortError ? '<span class="error" style="color: red;">' . $cohortError . '</span>' : '') . '
                        </div>
                    </div>
                    <div class="add-field">
                        <label>Role<span style="color: red;">*</span></label>
                        <div class="input">
                            <input type="text" name="role" id="role" value="Trainee" readonly>
                            ' . ($roleError ? '<span class="error" style="color: red;">' . $roleError . '</span>' : '') . '
                        </div>
                    </div>
                    <div class="add-field">
                        <label>Password<span style="color: red;">*</span></label>
                        <div class="input">
                            <input type="password" name="password" id="password" placeholder="Trainee password">
                            ' . ($passwordError ? '<span class="error" style="color: red;">' . $passwordError . '</span>' : '') . '
                        </div>';
    $addTrainee .= '<div class="add-btn mt-2">
                        <button type="submit" name="createtraineebtn">CREATE TRAINEE</button>
                    </div>
                </div>';
    $addTrainee .= '</form>';
    $addTrainee .= '</div>';
    $addTrainee .= '</div>';
    $addTrainee .= '</div>';
    $addTrainee .= '</div>';


    return $addTrainee;
}
add_shortcode('addTrainee', 'addTrainee');


function addTrainer($attrs)
{
    $dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
    $taskListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
    $traineesDark = get_template_directory_uri() . "/assets/trainees-dark.png";
    $trainersDark = get_template_directory_uri() . "/assets/trainer-dark.png";
    $logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
    $account = get_template_directory_uri() . "/assets/account.png";

    if (isset($_POST['logout'])) {
        wp_logout();
        wp_redirect('/easy-manage/login');
    }

    $firstnameError = $lastnameError = $emailError = $roleError = $passwordError = '';
    $firstname = '';
    $lastname = '';
    $email = '';
    $err = '';

    global $wpdb;
    global $successmsg;
    global $errormsg;
    $successmsg = false;
    $errormsg = false;

    if (isset($_POST['createtraineebtn'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];


        if (empty($firstname)) {
            $firstnameError = "First Name is required!";
        } else {
            $firstname = test_input($_POST['firstname']);
        }

        if (empty($lastname)) {
            $lastnameError = "Last Name is required!";
        } else {
            $lastname = test_input($_POST['lastname']);
        }

        if (empty($email)) {
            $emailError = "Email is required!";
        } else {
            $email = test_input($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format!";
            }
        }

        if (empty($role)) {
            $roleError = "Role is required!";
        } else {
            $role = test_input($_POST['role']);
        }

        if (empty($password)) {
            $passwordError = "Password is required!";
        } else {
            $password = test_input($_POST['password']);
        }

            

            $user_id = wp_create_user($email, $password, $email);

            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, 'last_name', $lastname);
                update_user_meta($user_id, 'role', $role);

                $user = wp_signon([
                    'user_login' => $email,
                    'user_password' => $password,
                    'remember' => true
                ]);

                if (!is_wp_error($user)) {
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);
                    do_action('wp_login', $user->user_login, $user);

                    $successmsg = true;
                    // wp_redirect('easy-manage/users');
                    exit;
                } else {
                    $errormsg = true;
                    $err = "Invalid email or password";
            } 
            }
    }

    $addTrainer = '';
    $addTrainer .= '<div class="page">
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
                        <img src=' . $dashboardDark . ' alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/project-manager-tasks-list" class="trainee-dash">
                        <img src=' . $taskListDark . ' alt="">
                        <p>Tasks List</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/project-manager-trainees" class="trainee-dash">
                        <img src=' . $traineesDark . ' alt="">
                        <p>Trainees</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/trainers" class="trainee-dash">
                        <img src=' . $trainersDark . ' alt="">
                        <p>Trainers</p>
                    </a>
                </article>
                <div class="sidebar-line"></div>
                <article>
                    <a href="#" class="trainee-dash">
                        <form action="" method="POST">
                            <div class="logoutform">
                                <img src=' . $logoutDark . ' alt="">
                                <input class="logout" name="logout" type="submit" value="Logout">
                            </div>
                        </form>
                    </a>
                </article>
            </div>
            <div class="account-new">
                <img src=' . $account . ' alt="">
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
            </nav>
        </div>

        <hr>';
    $addTrainer .= '<div class="container">';
    echo '<div class="alert alert-success" role="alert" id="success">
                New project created successfully
             </div>';

    echo '<script> document.getElementById("success").style.display = "none"; </script>';

    if ($successmsg == true) {
        echo '<script> document.getElementById("success").style.display = "flex"; </script>';

        echo    '<script> 
                        setTimeout(function(){
                            document.getElementById("success").style.display ="none";
                        }, 3000);
                    </script>';
    }

    echo '<div class="alert alert-danger" role="alert" id="error">
    An error occurred while creating a new project. Please try again!
  </div>';

echo '<script> document.getElementById("error").style.display = "none"; </script>';

if ($errormsg == true) {
echo '<script> document.getElementById("error").style.display = "flex"; </script>';

echo    '<script> 
             setTimeout(function(){
                 document.getElementById("error").style.display ="none";
             }, 3000);
         </script>';
}
    $addTrainer .= '<div class="add-content shadow-sm d-flex flex-column bg-light p-4">';
    $addTrainer .= '<form action="' . esc_url($_SERVER["REQUEST_URI"]) . '" method="post">';
    $addTrainer .= '<h2 class="text-center">Add New Trainer</h2>';
    $addTrainer .= '<p style="color: red;"><span class="error">* required field</span></p>';
    $addTrainer .= '<div class="form-content">
                                <div class="add-field">
                                    <label>First Name<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="text" name="firstname" id="firstname" placeholder="Trainer first name">
                                        ' . ($firstnameError ? '<span class="error" style="color: red;">' . $firstnameError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Last Name<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="text" name="lastname" id="lastname" placeholder="Trainer last name">
                                        ' . ($lastnameError ? '<span class="error" style="color: red;">' . $lastnameError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Email<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="email" name="email" id="email" placeholder="Trainer email">
                                        ' . ($emailError ? '<span class="error" style="color: red;">' . $emailError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Role<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="text" name="role" id="role" value="Trainer" readonly>
                                        ' . ($roleError ? '<span class="error" style="color: red;">' . $roleError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Password<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="password" name="password" id="password" placeholder="Trainer password">
                                        ' . ($passwordError ? '<span class="error" style="color: red;">' . $passwordError . '</span>' : '') . '
                                    </div>
                                </div>
                            </div>';
    $addTrainer .= '<div class="add-btn mt-2">
                        <button type="submit" name="createtraineebtn">CREATE TRAINER</button>
                    </div>
                </div>';
    $addTrainer .= '</form>';
    $addTrainer .= '</div>';
    $addTrainer .= '</div>';
    $addTrainer .= '</div>';
    $addTrainer .= '</div>';


    return $addTrainer;
}
add_shortcode('addTrainer', 'addTrainer');

function addProjectManager($attrs)
{
    $dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
    $tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
    $usersDark = get_template_directory_uri() . "/assets/users-dark.png";
    $addNewProjectManager = get_template_directory_uri() . "/assets/add-user.png";
    $logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
    $account = get_template_directory_uri() . "/assets/account.png";


    // if (is_user_logged_in()) {
    //     // wp_redirect('/easy-manage/admin-dashboard');
    //     exit;
    // }

    if (isset($_POST['logout'])) {
        wp_logout();
        wp_redirect('/easy-manage/login');
    }

    $firstnameError = $lastnameError = $emailError = $roleError = $passwordError = '';
    $firstname = '';
    $lastname = '';
    $email = '';
    $err = '';

    global $wpdb;
    global $successmsg;
    global $errormsg;
    $successmsg = false;
    $errormsg = false;

    if (isset($_POST['createpmbtn'])) {
        if (empty($_POST['firstname'])) {
            $firstnameError = "First Name is required!";
        } else {
            $firstname = test_input($_POST['firstname']);
        }

        if (empty($_POST['lastname'])) {
            $lastnameError = "Last Name is required!";
        } else {
            $lastname = test_input($_POST['lastname']);
        }

        if (empty($_POST['email'])) {
            $emailError = "Email is required!";
        } else {
            $email = test_input($_POST['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format!";
            }
        }

        if (empty($_POST['role'])) {
            $roleError = "Role is required!";
        } else {
            $role = test_input($_POST['role']);
        }

        if (empty($_POST['password'])) {
            $passwordError = "Password is required!";
        } else {
            $password = test_input($_POST['password']);
        }

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $password = $_POST['password'];

            $user_id = wp_create_user($email, $password, $email);

            if (!is_wp_error($user_id)) {
                update_user_meta($user_id, 'last_name', $lastname);
                update_user_meta($user_id, 'role', $role);

                $user = wp_signon([
                    'user_login' => $email,
                    'user_password' => $password,
                    'remember' => true
                ]);

                if (!is_wp_error($user)) {
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);
                    do_action('wp_login', $user->user_login, $user);

                    $successmsg = true;
                    // wp_redirect('easy-manage/users');
                    exit;
                } else {
                    $errormsg = true;
                    $err = "Invalid email or password";
            } 
            }
    }

    $addProjectManager = '';
    $addProjectManager .= '<div class="page">';
    $addProjectManager .= '<div class="sidebar">
        <div class="logo">
            <a href="#">
                <h3>Eazzy Manage</h3>
            </a>
        </div>
        <div class="sidebar-content">
        <article>
                    <a href="/easy-manage/admin-dashboard" class="trainee-dash">
                        <img src=' . $dashboardDark . ' alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/admin-tasks-list" class="trainee-dash">
                        <img src=' . $tasksListDark . ' alt="">
                        <p>Tasks List</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/users" class="trainee-dash">
                        <img src=' . $usersDark . ' alt="">
                        <p>Users</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/add-project-manager" class="current-page">
                        <img src=' . $addNewProjectManager . ' alt="">
                        <p>Add Project Manager</p>
                    </a>
                </article>
                <div class="sidebar-line"></div>
                <article>
                    <a href="#" class="trainee-dash">
                        <form action="" method="POST">
                            <div class="logoutform">
                                <img src=' . $logoutDark . ' alt="">
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
                    <img src=' . $account . ' alt="">
                    <div class="profile">
                        <h4>admin</h4>
                        <p>admin</p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>';
    $addProjectManager .= '<div class="container">';
    if($successmsg):
        $addProjectManager .= '<div class="alert alert-success" role="alert" id="successalert">
                                    Project Manager created successfully!
                                </div>
                                <script>
                                    document.getElementById("successalert").style.display = "flex";
                                    setTimeout(function() {
                                        document.getElementById("successalert").style.display = "none";
                                    }, 3000);
                                </script>';
        endif;
        if ($errormsg):
        $addProjectManager .= '<div class="alert alert-danger" role="alert" id="erroralert">
                                    Project Manager not created! Please try again.
                                </div>
                                <script>
                                    document.getElementById("erroralert").style.display = "flex";
                                    setTimeout(function() {
                                        document.getElementById("erroralert").style.display = "none";
                                    }, 3000);
                                </script>';
    endif;
    $addProjectManager .= '<div class="add-content shadow-sm d-flex flex-column bg-light p-4">';
    $addProjectManager .= '<form action="' . esc_url($_SERVER["REQUEST_URI"]) . '" method="post">';
    $addProjectManager .= '<h2 class="text-center">Add New Project Manager</h2>';
    $addProjectManager .= '<p style="color: red;"><span class="error">* required field</span></p>';
    $addProjectManager .= '<div class="form-content">
                                <div class="add-field">
                                    <label>First Name<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="text" name="firstname" id="firstname" placeholder="Project Manager first name">
                                        ' . ($firstnameError ? '<span class="error" style="color: red;">' . $firstnameError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Last Name<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="text" name="lastname" id="lastname" placeholder="Project Manager last name">
                                        ' . ($lastnameError ? '<span class="error" style="color: red;">' . $lastnameError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Email<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="email" name="email" id="email" placeholder="Project Manager email">
                                        ' . ($emailError ? '<span class="error" style="color: red;">' . $emailError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Role<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="text" name="role" id="role" value="Project Manager" readonly>
                                        ' . ($roleError ? '<span class="error" style="color: red;">' . $roleError . '</span>' : '') . '
                                    </div>
                                </div>
                                <div class="add-field">
                                    <label>Password<span style="color: red;">*</span></label>
                                    <div class="input">
                                        <input type="password" name="password" id="password" placeholder="Project Manager password">
                                        ' . ($passwordError ? '<span class="error" style="color: red;">' . $passwordError . '</span>' : '') . '
                                    </div>
                                </div>
                            </div>';

    $addProjectManager .= '<div class="add-btn-pm mt-2">
                        <button type="submit" name="createpmbtn">CREATE PROJECT MANAGER</button>
                    </div>
                </div>';
    $addProjectManager .= '</form>';
    $addProjectManager .= '</div>';
    $addProjectManager .= '</div>';
    $addProjectManager .= '</div>';
    $addProjectManager .= '</div>';


    return $addProjectManager;
}
add_shortcode('addProjectManager', 'addProjectManager');


//fetch users

function fetch_all_users()
{
    global $wpdb;

    $users = [];

    $query = "
        SELECT users.user_login , users.user_email, meta1.meta_value AS lastname, meta2.meta_value AS role
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'role'
    ";

    $results = $wpdb->get_results($query);

    foreach ($results as $result) {
        $userdata = [
            'user_login' => $result->user_login,
            'user_email' => $result->user_email,
            'lastname'   => $result->lastname,
            'role'       => $result->role,
        ];

        $users[] = $userdata;
    }

    return $users;
    // var_dump($users);
}


function fetch_user()
{
    $current_user = [];
    $user = wp_get_current_user();
    $current_user['email'] = $user->user_email;
    $id = $user->ID;
    $current_user['id'] = $user->ID;
    $current_user['firstname'] = $user->user_login;
    $user_meta = get_user_meta($id);
    $lastname = $user_meta['last_name'][0];
    $current_user['lastname'] = $lastname;
    $role = $user_meta['role'][0];
    $current_user['role'] = $role;

    return $current_user;
}

function update_user_data($user_id, $meta_key, $meta_value)
{
    try {
        if (isset($meta_value)) {
            $response = update_user_meta($user_id, $meta_key, $meta_value);
            return $response ? true : false;
        }
        return false;
    } catch (Exception $e) {
        echo "An error occurred while updating user data";
        return false;
    }
}

//custom messages function
// function custom_error_messages($error){
//     global $errors;

//     if (is_wp_error($errors) && empty($errors->errors)){
//         echo  '<div class="alert alert-danger" role="alert" id="errormsg">
//         Action successful
//      </div>';
//      $_POST = '';
//     }else {

//         if( is_wp_error( $errors ) && ! empty( $errors->errors ) ){

//           $error_messages = $errors->get_error_messages(); 
//           foreach( $error_messages as $k => $message ){
//               echo '<div class="alert alert-danger" role="alert" id="errormsg">';
//               echo '<p>' . $message . '</p>';
//               echo '</div>';

//           }

//         }

//     }
// }

//limit login attempts for admin dashboard
global $transient_value;
$transient_value = 'login_attempts';

global $login_limits;
$login_limit = 3;

global $blocked_access_time;
$blocked_access_time = 5 * 60; //5 minutes

global $attempts;
$attempts = 'attempts';
function limit_login_attempts($user)
{
    global $transient_value;
    global $login_limits;
    global $blocked_access_time;

    $transient = get_transient($transient_value);

    if ($transient && is_array($transient)) {
        $trials = $transient['attempts'];

        if ($trials >= $login_limits) {
            return new WP_Error('too_many_attempts', '<strong>ERROR</strong>: You have exceeded the number of login attempts. Please try again in ' . convert_to_seconds($blocked_access_time));
        }

        return new WP_Error('login_error', "Wrong password. " . ($login_limits - $trials) . " trials remaining");
    }

    return $user;
}

add_action('authenticate', 'limit_login_attempts', 40, 3);


function failed_to_login()
{
    global $transient_value;
    global $login_limits;
    global $blocked_access_time;

    $transient = get_transient($transient_value);

    if ($transient && is_array($transient)) {
        $trials = $transient['attempts'];
        if ($trials >= $login_limits) {
            return new WP_Error('too_many_attempts', '<strong>ERROR</strong>: You have exceeded the number of login attempts. Please try again in ' . convert_to_seconds($blocked_access_time) . ' minutes');
        }
        $transient['attempts'] = $trials + 1;
        set_transient($transient_value, $transient, $blocked_access_time);
    } else {
        set_transient($transient_value, ['attempts' => 1], $blocked_access_time);
    }
}
add_action('wp_login_failed', 'failed_to_login');

function convert_to_seconds($seconds)
{
    if ($seconds < 60) {
        return $seconds . " seconds";
    } else {
        $minutes = floor($seconds / 60);
        $remaining_seconds = $seconds % 60;

        return $minutes . " minutes " . ($remaining_seconds > 0 ? " and " . $remaining_seconds . " seconds" : "");
    }
}

//limit login attempts
function limit_user_login_attempts()
{
    $login_email = $_POST['email'];
    $login_password = $_POST['password'];
    $login_attempts = get_option('login_attempts');
    if ($login_attempts) {
        $login_attempts = $login_attempts + 1;
        update_option('login_attempts', $login_attempts);
    } else {
        update_option('login_attempts', 1);
    }
    if ($login_attempts > 3) {
        $login_attempts = 0;
        update_option('login_attempts', $login_attempts);
        wp_die('You have exceeded the number of login attempts. Please try again in 5 minutes');
    }
}

add_action('wp_login_failed', 'limit_login_attempts');

// function redirect_after_logout(){
//     wp_redirect(home_url());
//     exit;
// }

// add_action('wp_logout', 'redirect_after_logout');

// function redirect_after_login(){
//     wp_redirect(home_url());
//     exit;
// }

//add users
function add_users()
{
    add_role('trainee', 'Trainee', array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
    ));

    add_role('trainer', 'Trainer', array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
    ));

    add_role('project_manager', 'Project Manager', array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
    ));
}
add_action('init', 'add_users');
