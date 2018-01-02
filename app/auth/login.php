<?php

declare(strict_types=1);

require __DIR__.'/../autoload.php';

// This is the script that logs in users

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);

    $users = getUsers($pdo);

    // Loop through user array and compare username and email to user input
    forEach($users as $user) {
        if ($username === $user['username'] || $username === $user['email']) {
            if (password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user'] = [
                    'id' => (string) $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                ];
                unset($_SESSION['errors']['login']);
                redirect('/');
            } else {
                $_SESSION['errors']['login'] = 'The password was incorrect.';
                redirect('/login.php');
            }
        }
    }

    if (!isset($_SESSION['user'])) {
        $_SESSION['errors']['login'] = 'The username or email does not exist.';
        redirect('/login.php');
    }
} else {
    redirect('/login.php');
}
