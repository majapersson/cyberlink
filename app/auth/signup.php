<?php

    declare(strict_types=1);

    require __DIR__.'/../autoload.php';

    if (isset($_POST['username'])) {
        unset($_SESSION['errors']['user']);
        // Save input in temporary variables
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Loop through user array and compare username and email to user input
        $users = getUsers($pdo);

        forEach($users as $user) {
            if ($username === $user['username']) {
                $_SESSION['errors']['user'][] = 'The username is already taken.';
            }
            if ($email === $user['email']) {
                $_SESSION['errors']['user'][] = 'The email is already registered.';
            }
        }

        if (isset($_SESSION['errors']['user'])) {
            redirect('/signup.php');
        }

        setUser($pdo, $username, $email, $passwordHash);

        // Get user info from database
        $query = $pdo-> prepare('SELECT * FROM users WHERE username=:username;');

        if(!$query) {
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':username', $username, PDO::PARAM_STR);
        $query-> execute();
        $user = $query-> fetch(PDO::FETCH_ASSOC);

        // Save user in session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
        ];

        redirect('/');
    }
