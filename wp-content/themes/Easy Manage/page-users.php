<?php

/**
 * Template Name: Users
 */


?>


<?php
$dashboardDark = get_template_directory_uri() . "/assets/dashboard-dark.png";
$tasksListDark = get_template_directory_uri() . "/assets/tasks-list-dark.png";
$usersimg = get_template_directory_uri() . "/assets/users.png";
$addTraineeDark = get_template_directory_uri() . "/assets/add-user-dark.png";
$logoutDark = get_template_directory_uri() . "/assets/logout-dark.png";
$activate = get_template_directory_uri() . "/assets/activate.png";
$deactivate = get_template_directory_uri() . "/assets/deactivate.png";

$searchimg = get_template_directory_uri() . "/assets/search.png";
$totalTasks = get_template_directory_uri() . "/assets/total tasks.png";
$group = get_template_directory_uri() . "/assets/group.png";
$progress = get_template_directory_uri() . "/assets/progress.png";
$account = get_template_directory_uri() . "/assets/account.png";

if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

// $users_per_page = 5;
// $current_page = isset($_GET['page']) ? absint($_GET['page']) : 1;
// $offset = ($current_page - 1) * $users_per_page;

// $args = array(
//     'orderby' => 'user_registered',
//     'order' => 'DESC',
//     'number' => $users_per_page,
//     'offset' => $offset,
// );

// $users_query = new WP_User_Query($args);
// $no_users = $users_query->get_results();

// $total_users = $users_query->get_total();
// $total_pages = ceil($total_users / $users_per_page);


$users = fetch_users();

$user_logged_in = wp_get_current_user();
$user_role = get_user_meta($user_logged_in->ID, 'wp_capabilities', true);
$user_role = array_keys($user_role)[0];

if (isset($_POST['activatebtn'])) {
    $token = $_COOKIE['token'];
    $user_id = $_POST['ID'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/users/' . $user_id . '/activate';
    $args = array(
        'method' => 'PUT',
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
        echo 'User activated successfully';
        $users = fetch_users();
    } else {
        echo 'Error activating user: ' . $response_code;
       
    }
}

if (isset($_POST['deactivatebtn'])) {
    $token = $_COOKIE['token'];
    $user_id = $_POST['ID'];

    $url = 'http://localhost/easy-manage/wp-json/easymanage/v1/users/' . $user_id . '/deactivate';
    $args = array(
        'method' => 'PUT',
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
        echo 'User deactivated successfully';
        $users = fetch_users();
    } else {
        echo 'Error deactivating user: ' . $response_code;
    }
}


if(isset($_GET['search'])){
    $users = admin_search_users();
     
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
        <div class="sidebar-content">
            <article>
                <a href="/easy-manage/admin-dashboard" class="trainee-dash">
                    <img src="<?php echo $dashboardDark ?>" alt="">
                    <p>Dashboard</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/admin-tasks-list" class="trainee-dash">
                    <img src="<?php echo $tasksListDark; ?>" alt="">
                    <p>Tasks List</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/users" class="current-page">
                    <img src="<?php echo $usersimg; ?>" alt="">
                    <p>Users</p>
                </a>
            </article>
            <article>
                <a href="/easy-manage/add-project-manager" class="trainee-dash">
                    <img src="<?php echo $addTraineeDark; ?>" alt="">
                    <p>Add Project Manager</p>
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
    </div>


    <div class="page-content">

        <div class="main-trainee-nav">
            <nav class="trainee-nav">
                <div class="trainee-welcome-text">
                    <h3>Welcome,
                        <?php echo $user_logged_in->user_login; ?>
                    </h3>
                    <p>Today is Saturday, 10 June 2023</p>
                </div>
                <div class="account">
                    <img src="<?php echo $account; ?>" alt="">
                    <div class="profile">
                        <h4>
                            <?php echo $user_logged_in->user_login; ?>
                        </h4>
                        <p>
                            <?php echo $user_role; ?>
                        </p>
                    </div>
                </div>
            </nav>
        </div>
        <hr>

        <div class="search-bar mb-1">
            <form action="" method="get">
                <label for="search">
                    <img src="<?php echo $searchimg; ?>" alt="" onclick="performSearch()">
                </label>
                <input type="search" name="search" id="search" placeholder="Search any user">
            </form>
        </div>
        
        <script>
            function performSearch() {
                document.querySelector('form').submit();
            }
        </script>

        <div class="assigned-tasks-table">
            <h3>Trainers</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Second Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="tasks-body">
                    <?php if(empty($users)){ ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No users found</td>
                        </tr>
                    <?php } else {
                    foreach ($users as $user) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $user->firstname; ?>
                            </td>
                            <td>
                                <?php echo $user->lastname; ?>
                            </td>
                            <td>
                                <?php echo $user->email; ?>
                            </td>
                            <td>
                                <?php echo $user->role; ?>
                            </td>
                            <td class="tasksbtn">
                                <form action="" method="post">
                                    <input type="hidden" name="ID" value="<?php echo $user->ID; ?>">
                                    <?php if ($user->is_active == 1 && $user->is_deleted == 0) { ?>
                                        <button type="submit" name="deactivatebtn">
                                            <img src="<?php echo $deactivate; ?>" alt="">
                                            Deactivate
                                        </button>
                                    <?php } else if ($user->is_active == 0 && $user->is_deleted == 0) { ?>
                                            <button type="submit" name="activatebtn">
                                                <img src="<?php echo $activate; ?>" alt="">
                                                Activate
                                            </button>
                                        <?php } else {
                                        echo "Invalid status";
                                    } ?>
                                </form>

                            </td>
                        </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php get_footer(); ?>