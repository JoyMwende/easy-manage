<?php

/**
 * @package Easy Manage Plugin
 */

namespace Inc\Pages;

class Login{
    public function login()
    {
        // if (isset($_POST['loginbtn'])) {

        //     $args = [
        //         'method' => 'POST',
        //         'headers' => array(
        //             'Content-Type' => 'application/json',
        //         ),
        //         'body' => array(
        //             'email' => $_POST['email'],
        //             'password' => $_POST['password']
        //         )
        //     ];

        //     $result = wp_remote_post('http://localhost/customtheme/wp-json/jwt-auth/v1/token', $args);

        //     echo '<pre>';
        //     $token = (json_decode(wp_remote_retrieve_body($result)));
        //     var_dump($token->token);
        //     setcookie('token', $token->token, time() + (86400 * 30), '/', 'localhost');
        //     echo '</pre>';
        // }
    }
}