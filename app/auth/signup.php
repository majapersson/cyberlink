<?php

    declare(strict_types=1);

    require __DIR__.'/../autoload.php';

    if (isset($_POST['username'])) {
        // Save input in temporary variables
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert info with hashed password into user database
        $query = $pdo-> prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');

        if(!$query) {
            die(var_dump($pdo->errorInfo()));
        }

        $query-> bindParam(':username', $username, PDO::PARAM_STR);
        $query-> bindParam(':email', $email, PDO::PARAM_STR);
        $query-> bindParam(':password', $passwordHash, PDO::PARAM_STR);

        $query-> execute();

        // // Get user info from database
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
