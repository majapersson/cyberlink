<?php

    declare(strict_types=1);

    require __DIR__.'/../autoload.php';

    // This is the script that logs in users

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

        $query = $pdo-> prepare('SELECT * FROM users WHERE username=:username OR email=:username');

        if(!$query) {
            die(var_dump($pdo->errorInfo()));
        }

        $query-> bindParam(':username', $username, PDO::PARAM_STR);
        $query-> execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        // When user is fetched from database
        if (!isset($user['id'])) {
            $_SESSION['errors']['login'] = 'The username or email does not exist.';
            redirect('/login.php');
        } else {
            if (password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
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
