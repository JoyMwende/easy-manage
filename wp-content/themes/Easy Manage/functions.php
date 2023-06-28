<?php

function easymanagetheme_script_enqueue()
{
    // introduce bootstrap
    wp_register_style('bootstrapstyling', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', [], '5.2.3', 'all');
    wp_enqueue_style('bootstrapstyling');

    wp_register_script('jsbootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js', [], '5.2.3', false);
    wp_enqueue_script('jsbootstrap');

    wp_enqueue_style('style', get_template_directory_uri() . '/style.css', [], '1.0', 'all');
    // wp_enqueue_script('main-script', get_template_directory_uri() . '/script.js', [], false, true);
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

    if (isset($_POST['loginbtn'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email)) {
            $emailError = 'Email is required';
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailError = "Invalid email format!";
            }
        }

        if (empty($password)) {
            $passwordError = 'Password is required';
        }

        if (empty($emailError) && empty($passwordError)) {
            $args = array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'body' => json_encode(
                    array(
                        'username' => $email,
                        'password' => $password
                    )
                )
            );

            $response = wp_remote_post('http://localhost/easy-manage/wp-json/jwt-auth/v1/token', $args);


            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo 'Error: ' . $error_message;
                return;
            } else {
                $response_body = wp_remote_retrieve_body($response);
                $response_data = json_decode($response_body, true);

                if (isset($response_data['token'])) {
                    $token = $response_data['token'];
                    setcookie('token', $token, time() + (86400 * 30), '/', 'localhost');

                    // echo '<pre>';
                    // var_dump($token);
                    // echo '</pre>';

                    $user = wp_authenticate($email, $password);

                    if (is_wp_error($user)) {
                        echo 'Invalid login credentials';
                    } else {
                        $user_id = $user->ID;
                        $user_login = $user->user_login;

                        wp_set_current_user($user_id, $user_login);
                        wp_set_auth_cookie($user_id);
                        do_action('wp_login', $user_login);

                        $user_roles = $user->roles;

                        switch ($user_roles[0]) {
                            case 'administrator':
                                wp_redirect('/easy-manage/admin-dashboard');
                                break;
                            case 'project_manager':
                                wp_redirect('/easy-manage/project-manager-dashboard');
                                break;
                            case 'trainer':
                                wp_redirect('/easy-manage/trainer-dashboard');
                                break;
                            case 'trainee':
                                wp_redirect('/easy-manage/trainee-dashboard');
                                break;
                            default:
                                echo 'Invalid login credentials';
                                break;
                        }

                        exit;
                    }
                } else {
                    echo 'Invalid login credentials';
                }
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
                            <input type="email" name="email" id="email" placeholder="Enter your email" value="">
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
// function limit_user_login_attempts()
// {
//     $login_email = $_POST['email'];
//     $login_password = $_POST['password'];
//     $login_attempts = get_option('login_attempts');
//     if ($login_attempts) {
//         $login_attempts = $login_attempts + 1;
//         update_option('login_attempts', $login_attempts);
//     } else {
//         update_option('login_attempts', 1);
//     }
//     if ($login_attempts > 3) {
//         $login_attempts = 0;
//         update_option('login_attempts', $login_attempts);
//         wp_die('You have exceeded the number of login attempts. Please try again in 5 minutes');
//     }
// }

// add_action('wp_login_failed', 'limit_login_attempts');

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
    add_role(
        'trainee',
        'Trainee',
        array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => false,
        )
    );

    add_role(
        'trainer',
        'Trainer',
        array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
        )
    );

    add_role(
        'project_manager',
        'Project Manager',
        array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
        )
    );
}
add_action('init', 'add_users');


//connecting with REST API
global $token;


//GET Requests
function count_total_users()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/total-users';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}



function count_total_project_managers()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/total-project-managers';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No Project Managers found';
    }
}

function count_total_trainers()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/total-trainers';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainers found';
    }
}

function count_total_trainees()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/total-trainees';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainees found';
    }
}

function count_total_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/total-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function admin_get_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function admin_get_single_task()
{
    $token = $_COOKIE['token'];
    $task_id = $_GET['id'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/tasks/' . $task_id;
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No task found';
    }
}


function fetch_users()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/users';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function pm_get_tasks(){
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function pm_get_single_task(){
    $token = $_COOKIE['token'];
    $task_id = $_GET['id'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/tasks/' . $task_id;
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No task found';
    }
}

function pm_get_trainers(){
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/trainers';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainers found';
    }
}

function pm_get_trainees(){
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/trainees';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainees found';
    }
}

function count_total_trainees_pm()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/total-trainees';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainees found';
    }
}

function count_total_tasks_pm()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/total-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function count_total_trainers_pm()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/total-trainers';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainers found';
    }
}

function count_total_trainees_trainer()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/total-trainees';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainees found';
    }
}

function count_total_tasks_trainer()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/total-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function count_total_submitted_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/total-submitted-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function count_total_tasks_in_progress()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/total-tasks-in-progress';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function trainer_get_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No tasks found';
    }
}

function trainer_get_single_task()
{
    $token = $_COOKIE['token'];
    $task_id = $_GET['id'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/tasks/' . $task_id;
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No task found';
    }
}

function trainer_get_trainees()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/trainees';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainees found';
    }
}

function get_assigned_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/assignedtasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainees found';
    }
}

function get_completed_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/completedtasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No trainees found';
    }
}

function trainee_get_single_task()
{
    $token = $_COOKIE['token'];
    $task_id = $_GET['id'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/assignedtasks/' . $task_id;
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No task found';
    }
}

function count_total_assigned_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/assignedtasks/count';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function count_total_started_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/startedtasks/count';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function count_total_completed_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/completedtasks/count';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function count_total_group_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/assignedtasks/countgroup';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function fetch_latest_created_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/latest-created-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function fetch_latest_created_tasks_pm()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/latest-created-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function fetch_latest_submitted_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/latest-submitted-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function fetch_cohorts()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/latest-created-tasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}


function admin_search_users()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/search-users?search=' . $_GET['search'];
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function pm_search_users()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/search-users?search=' . $_GET['search'];
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function trainer_search_users()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/search-users?search=' . $_GET['search'];
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function trainee_search_users()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/search-users?search=' . $_GET['search'];
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function admin_search_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/search-tasks?search=' . $_GET['search'];
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function pm_search_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/search-tasks?search=' . $_GET['search'];
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function trainer_search_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/search-tasks?search=' . $_GET['search'];
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}

function newest_tasks()
{
    $token = $_COOKIE['token'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v3/newesttasks';
    $args = array(
        'method' => 'GET',
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        )
    );

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo 'Error: ' . $error_message;
        return;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code === 200) {
        $data = json_decode($response_body);
        return $data;
    } else {
        echo 'Error: ' . $response_code . '<br>';
        echo 'Response Body: ' . $response_body . '<br>';
        return 'No users found';
    }
}