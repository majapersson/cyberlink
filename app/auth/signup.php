<?php

    declare(strict_types=1);

    require __DIR__.'/../autoload.php';

    if (isset($_POST['username'])) {
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

        $query = $pdo-> prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');

        if(!$query) {
            die(var_dump($pdo->errorInfo()));
        }

        $query-> bindParam(':username', $username, PDO::PARAM_STR);
        $query-> bindParam(':email', $email, PDO::PARAM_STR);
        $query-> bindParam(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

        $query-> execute();

        redirect('/');
    }
