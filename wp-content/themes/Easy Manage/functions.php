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
            'lastname' => $result->lastname,
            'role' => $result->role,
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
$token = isset($GLOBALS['token']) ? $GLOBALS['token'] : '';


//GET Requests
function count_total_users()
{
    $token = isset($GLOBALS['token']) ? $GLOBALS['token'] : '';

    $url = 'https://easy-manage.com/wp-json/easymanage/v1/total-users';
    $args = array(
        'method' => 'GET',
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ),
        'body' => null,
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
    $result = wp_remote_get('http://localhost/easy-manage/wp-json/easymanage/v1/total-project-managers', [
        'method' => 'GET',
        'headers' => ['Authorization => Bearer ' . $GLOBALS['token']]
    ]);

    $response = json_decode(wp_remote_retrieve_body($result));

    if ($response) {
        return $response;
    } else {
        echo "No project managers found";
    }
}

function count_total_trainers()
{
    $result = wp_remote_get('http://localhost/easy-manage/wp-json/easymanage/v1/total-trainers', [
        'method' => 'GET',
        'headers' => ['Authorization => Bearer ' . $GLOBALS['token']]
    ]);

    $response = json_decode(wp_remote_retrieve_body($result));

    if ($response) {
        return $response;
    } else {
        echo "No trainers found";
    }
}

function count_total_trainees()
{
    $result = wp_remote_get('http://localhost/easy-manage/wp-json/easymanage/v1/total-trainees', [
        'method' => 'GET',
        'headers' => ['Authorization => Bearer ' . $GLOBALS['token']]
    ]);

    $response = json_decode(wp_remote_retrieve_body($result));

    if ($response) {
        return $response;
    } else {
        echo "No trainees found";
    }
}

function count_total_tasks()
{
    $result = wp_remote_get('http://localhost/easy-manage/wp-json/easymanage/v1/total-tasks', [
        'method' => 'GET',
        'headers' => ['Authorization => Bearer ' . $GLOBALS['token']]
    ]);

    $response = json_decode(wp_remote_retrieve_body($result));

    if ($response) {
        return $response;
    } else {
        echo "No tasks found";
    }
}