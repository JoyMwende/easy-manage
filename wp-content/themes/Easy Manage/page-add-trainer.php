<?php
/**
 * Template Name: Add Trainer
 */
?>

<?php get_header(); ?>

<?php
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

$firstnameError = $lastnameError = $emailError = $roleError = $passwordError = $cohortnameError = '';


global $wpdb;
global $successmsg;
global $errormsg;
$successmsg = false;
$errormsg = false;

$user_logged_in = wp_get_current_user();
$user_role = $wpdb->get_row("SELECT users.user_login AS firstname, users.user_email AS email, meta1.meta_value AS lastname, meta2.meta_value AS role
        FROM {$wpdb->users} AS users
        LEFT JOIN {$wpdb->usermeta} AS meta1 ON meta1.user_id = users.ID AND meta1.meta_key = 'last_name'
        LEFT JOIN {$wpdb->usermeta} AS meta2 ON meta2.user_id = users.ID AND meta2.meta_key = 'wp_capabilities' WHERE id = $user_logged_in->ID");

// $user_logged_data = $loggged_in_user->user_email;

global $wpdb;
$table = $wpdb->prefix . 'cohorts';
$cohorts = $wpdb->get_results("SELECT * FROM $table WHERE is_deleted = 0");

if (isset($_POST['createtraineebtn'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $created_by = $_POST['created_by'];
    $cohort_name = $_POST['cohort_name'];


    if (empty($firstname)) {
        $firstnameError = "First Name is required!";
    } 

    if (empty($lastname)) {
        $lastnameError = "Last Name is required!";
    } 

    if (empty($email)) {
        $emailError = "Email is required!";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Invalid email format!";
        }
    }

    if (empty($role)) {
        $roleError = "Role is required!";
    } 

    if (empty($cohort_name)) {
        $cohortnameError = "Select Cohort Name!";
    } 

    if (empty($password)) {
        $passwordError = "Password is required!";
    }

    if (empty($firstnameError) && empty($lastnameError) && empty($emailError) && empty($roleError) && empty($cohortnameError) && empty($passwordError)) {

        $token = isset($GLOBALS['token']) ? $GLOBALS['token'] : '';


        if (!empty($token)) {
            $url = 'http://localhost/easy-manage/wp-json/easymanage/v2/trainers';

            $body = array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'role' => $role,
                'cohort_name' => $cohort_name,
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
        <div class="main-sidebar-content">
            <div class="sidebar-content">
                <article>
                    <a href="/easy-manage/project-manager-dashboard" class="trainee-dash">
                        <img src='<?php echo $dashboardDark; ?>' alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/project-manager-tasks-list" class="trainee-dash">
                        <img src='<?php echo $taskListDark; ?>' alt="">
                        <p>Tasks List</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/project-manager-trainees" class="trainee-dash">
                        <img src='<?php echo $traineesDark; ?>' alt="">
                        <p>Trainees</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/trainers" class="trainee-dash">
                        <img src='<?php echo $trainersDark; ?>' alt="">
                        <p>Trainers</p>
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
            <div class="account-new">
                <img src='<?php echo $account; ?>' alt="">
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
                        echo "Today is " . $current_date; ?>
                    </p>
                </div>
            </nav>
        </div>

        <hr>
        <div class="container">
            <?php
            echo '<div class="alert alert-success" role="alert" id="success">
                New trainer created successfully
             </div>';

            echo '<script> document.getElementById("success").style.display = "none"; </script>';

            if ($successmsg == true) {
                echo '<script> document.getElementById("success").style.display = "flex"; </script>';

                echo '<script> 
                        setTimeout(function(){
                            document.getElementById("success").style.display ="none";
                        }, 3000);
                    </script>';
            }

            echo '<div class="alert alert-danger" role="alert" id="error">
    An error occurred while creating a new trainer. Please try again!
  </div>';

            echo '<script> document.getElementById("error").style.display = "none"; </script>';

            if ($errormsg == true) {
                echo '<script> document.getElementById("error").style.display = "flex"; </script>';

                echo '<script> 
             setTimeout(function(){
                 document.getElementById("error").style.display ="none";
             }, 3000);
         </script>';
            }
            ?>
            <div class="add-content shadow-sm d-flex flex-column bg-light p-4">
                <form action="" method="post">
                    <h2 class="text-center">Add New Trainer</h2>
                    <p style="color: red;"><span class="error">* required field</span></p>
                    <div class="form-content">
                        <div class="add-field">
                            <label>First Name<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="text" name="firstname" id="firstname" placeholder="Trainer first name">
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
                                <input type="text" name="lastname" id="lastname" placeholder="Trainer last name">
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
                                <input type="email" name="email" id="email" placeholder="Trainer email">
                                <span class="error" style="color: red;">
                                    <?php if ($emailError) {
                                        echo $emailError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Role<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="text" name="role" id="role" value="trainer" readonly>
                                <span class="error" style="color: red;">
                                    <?php if ($roleError) {
                                        echo $roleError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Assign Cohort<span style="color: red;">*</span></label>
                            <div class="input">
                                <select name="cohort_name" class="">
                                    <?php
                                    foreach ($cohorts as $cohort):
                                        $name = $cohort->cohort_name; ?>
                                        <option>
                                            <?php echo $name; ?>
                                        </option>
                        
                                    <?php endforeach; ?>
                                </select>
                                <span class="error" style="color: red;">
                                    <?php if ($cohortnameError) {
                                        echo $cohortnameError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Password<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="password" name="password" id="password" placeholder="Trainer password">
                                <span class="error" style="color: red;">
                                    <?php if ($passwordError) {
                                        echo $passwordError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="add-btn mt-2">
                        <button type="submit" name="createtraineebtn">CREATE TRAINER</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>



<?php get_footer(); ?>