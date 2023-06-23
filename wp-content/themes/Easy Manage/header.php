<?php
if (isset($_POST['logout'])) {
    wp_logout();
    wp_redirect('/easy-manage/login');
}

// Perform user authentication and retrieve the token
$results = wp_remote_post('http://localhost/easy-manage/wp-json/jwt-auth/v1/token', [
    'body' => [
        'username' => 'admin',
        'password' => 'admin'
    ]
]);

if (is_wp_error($results)) {
    echo 'Error: ' . $results->get_error_message();
} else {
    // Retrieve the response body and decode it
    $response_body = wp_remote_retrieve_body($results);
    $response_data = json_decode($response_body);

    if (isset($response_data->token)) {
        $token = $response_data->token;

        $user_id = get_current_user_id();

        if ($user_id > 0) {
            $user_roles = get_user_meta($user_id, 'wp_capabilities', true);

            if (isset($user_roles['administrator']) && $user_roles['administrator'] == 1) {
                $GLOBALS['token'] = $token;
            } elseif (isset($user_roles['project_manager']) && $user_roles['project_manager'] == 1) {
                $GLOBALS['token'] = $token;
            } elseif (isset($user_roles['trainer']) && $user_roles['trainer'] == 1) {
                $GLOBALS['token'] = $token;
            } elseif (isset($user_roles['trainee']) && $user_roles['trainee'] == 1) {
                $GLOBALS['token'] = $token;
            } else {
                echo "You are not authorized to access this page.";
            }
        } else {
            echo "No user Logged in.";
        }
    } else {
        echo 'Token retrieval failed.';
    }
}

var_dump($GLOBALS['token']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Manage</title>
    <?php wp_head(); ?>
</head>

<body>