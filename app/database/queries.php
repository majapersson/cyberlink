<?php
/**
* Gets all users from database
*
* @param PDO $pdo
*/

function getUsers($pdo) {
    $query = $pdo-> query('SELECT id, username, email, password FROM users;');
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}


/**
* Gets user array from database based on user id
*
* @param PDO $pdo
* @param int $id
*/

function getUser ($pdo, int $id) {
    // Get user from ID
    $query = $pdo->prepare('SELECT * FROM users WHERE id=:id;');
    if(!$query){
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $id, PDO::PARAM_INT);
    $query-> execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    return $user;
}

/**
*  Saves new user info to database
*
* @param PDO $pdo
* @param string $username
* @param string $email
* @param string $password
*/

function setUser ($pdo, string $username, string $email, string $password) {
    $query = $pdo-> prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');

    if(!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);

    $query-> execute();
}

/**
* Updates user info in database
*
* @param PDO $pdo
* @param int $id
* @param string $email
* @param string $bio
*/

function updateInfo ($pdo, int $id, string $email, string $bio = null) {
    $query = $pdo-> prepare('UPDATE users SET email=:email, bio=:bio WHERE id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $id, PDO::PARAM_INT);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> bindParam(':bio', $bio, PDO::PARAM_STR);
    $query-> execute();

    // Get updated info
    $query = $pdo->prepare('SELECT * FROM users WHERE id=:id;');
    if(!$query){
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $id, PDO::PARAM_INT);
    $query-> execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    return $user;
}

/**
* Updates user avatar filepath in database and save image in avatar folder
*
* @param PDO $pdo
* @param array $image
* @param array $user
*/

function updateImage ($pdo, array $image, array $user) {
    $file = pathinfo($image['name']);
    $filename = $user['username'].'.'.$file['extension'];

    move_uploaded_file($image['tmp_name'], __DIR__.'/../../avatars/'.$filename);

    $query = $pdo-> prepare('UPDATE users SET image_url=:filename WHERE id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':filename', $filename, PDO::PARAM_STR);
    $query-> bindParam(':id', $user['id'], PDO::PARAM_INT);
    $query-> execute();
}

/**
* Updates user password in database
*
* @param PDO $pdo
* @param string $newPassword
* @param int $user_id
*/

function updatePassword ($pdo, string $newPassword, int $user_id) {
    $query = $pdo-> prepare('UPDATE users SET password=:password WHERE id=:id');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $password = password_hash($newPassword, PASSWORD_DEFAULT);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> bindParam(':id', $user_id, PDO::PARAM_INT);
    $query-> execute();
}
