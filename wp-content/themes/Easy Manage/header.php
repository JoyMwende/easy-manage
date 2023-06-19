<?php
    if (isset($_POST['logout'])) {
        wp_logout();
        wp_redirect('/easy-manage/login');
    }

    $results = wp_remote_post('http://localhost/easy-manage/wp-json/jwt-auth/v1/token', [
        'body' => [
            'username' => 'admin',
            'password' => 'admin'
        ]
    ]);
    $results = json_decode(wp_remote_retrieve_body($results));
    $GLOBALS['token'] = $results->token;
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy Manage</title>
    <?php wp_head();?>
</head>
<body>
    

