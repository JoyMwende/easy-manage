<?php
/**
 * Template Name: Add Trainee
 */
?>

<?php get_header(); ?>

<?php
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


global $wpdb;

global $successmsg;
global $errormsg;
$successmsg = false;
$errormsg = false;

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

// $user_logged_data = $loggged_in_user->user_email;
$table = $wpdb->prefix . 'cohorts';
$cohorts = $wpdb->get_results("SELECT * FROM $table WHERE is_deleted = 0");

if (isset($_POST['createtraineebtn'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $cohort = $_POST['cohort'];
    $created_by = $_POST['created_by'];

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

    if (empty($cohort)) {
        $cohortError = "Cohort is required!";
    }

    if (empty($role)) {
        $roleError = "Role is required!";
    }

    if (empty($password)) {
        $passwordError = "Password is required!";
    }

    if (empty($firstnameError) && empty($lastnameError) && empty($emailError) && empty($roleError) && empty($cohortError) && empty($passwordError)) {

        $token = isset($_COOKIE['token']) ? $_COOKIE['token'] : '';


        if (!empty($token)) {
            $url = 'http://localhost/easy-manage/wp-json/easymanage/v4/trainees';

            $body = array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'role' => $role,
                'cohort_name' => $cohort,
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
                    <a href="/easy-manage/trainer-dashboard" class="trainee-dash">
                        <img src='<?php echo $dashboardDark; ?>' alt="">
                        <p>Dashboard</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/tasks-list" class="trainee-dash">
                        <img src='<?php echo $tasksListDark; ?>' alt="">
                        <p>Tasks List</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/trainees" class="trainee-dash">
                        <img src='<?php echo $traineesDark; ?>' alt="">
                        <p>Trainees</p>
                    </a>
                </article>
                <article>
                    <a href="/easy-manage/add-trainee" class="current-page">
                        <img src='<?php echo $addNewTrainee; ?>' alt="">
                        <p>Add Trainee</p>
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
                        echo "Today is " . $current_date; ?>
                    </p>
                </div>
                <div class="btnadd">
                    <a href="/easy-manage/add-task/"><button type="submit">
                            <img src='<?php echo $plus; ?>' alt="">
                            New Project
                        </button></a>
                </div>
            </nav>
        </div>

        <hr>
        <div class="container">'
            <?php
            echo '<div class="alert alert-success" role="alert" id="success">
                New trainee created successfully
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
    An error occurred while creating a new trainee. Please try again!
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
            <div class="add-content shadow-sm d-flex flex-column bg-light p-4 ">
                <form action="" method="post">
                    <h2 class="text-center">Add New Trainee</h2>
                    <p style="color: red;"><span class="error">* required field</span></p>
                    <div class="form-content">
                        <div class="add-field">
                            <label>First Name<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="text" name="firstname" id="firstname" placeholder="Trainee first name">
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
                                <input type="taxt" name="lastname" id="lastname" placeholder="Trainee last name">
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
                                <input type="email" name="email" id="email" placeholder="Trainee email">
                                <span class="error" style="color: red;">
                                    <?php if ($emailError) {
                                        echo $emailError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Cohort<span style="color: red;">*</span></label>
                            <div class="input">
                                <select name="cohort" class="">
                                    <?php
                                    foreach ($cohorts as $cohort):
                                        $name = $cohort->cohort_name; ?>
                                        <option>
                                            <?php echo $name; ?>
                                        </option>
                                
                                    <?php endforeach; ?>
                                </select>
                                <span class="error" style="color: red;">
                                    <?php if ($cohortError) {
                                        echo $cohortError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-field">
                            <label>Role<span style="color: red;">*</span></label>
                            <div class="input">
                                <input type="text" name="role" id="role" value="trainee" readonly>
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
                                <input type="password" name="password" id="password" placeholder="Trainee password">
                                <span class="error" style="color: red;">
                                    <?php if ($passwordError) {
                                        echo $passwordError;
                                    } ?>
                                </span>
                            </div>
                        </div>
                        <div class="add-btn mt-2">
                            <button type="submit" name="createtraineebtn">CREATE TRAINEE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>


<?php get_footer(); ?>