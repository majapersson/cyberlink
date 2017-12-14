<?php
/*
* Checks database for user depending on with parameters are set
*
* Gets user array based in id if $id is set
* Gets user from username OR email if only $name is set
* Gets user
*
* @param PDO $pdo
* @param int $id
* @param string $name
* @param string $email
*/

function getUser ($pdo, int $id = null, string $name = null, string $email = null) {
    // Get user from ID
    if (!empty($id)) {
        $query = $pdo->prepare('SELECT * FROM users WHERE id=:id;');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':id', $id, PDO::PARAM_INT);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    // Get user from username/email (for login)
    if (!empty($name) && empty($email)){
        $query = $pdo->prepare('SELECT * FROM users WHERE username=:username OR email=:email;');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':username', $name, PDO::PARAM_STR);
        $query-> bindParam(':email', $name, PDO::PARAM_STR);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    // Get user from name AND email (for sign up)
    if (!empty($name) && !empty($email)) {
        // Check if username exists
        $query = $pdo->prepare('SELECT * FROM users WHERE username=:username');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':username', $name, PDO::PARAM_STR);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($user)) {
            $_SESSION['errors']['user'] = 'The username is already taken.';
            unset($user);
        }

        // Check if email exists
        $query = $pdo->prepare('SELECT * FROM users WHERE email=:email;');
        if(!$query){
            die(var_dump($pdo->errorInfo()));
        }
        $query-> bindParam(':email', $email, PDO::PARAM_STR);
        $query-> execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if(!empty($user)) {
            $_SESSION['errors']['user'] = 'The email is already in use.';
            unset($user);
        }
    }
}

/*
* Updates user info in database
*
* @param PDO $pdo
* @param int $id
* @param string $email
* @param string $bio
*/

function updateUser ($pdo, int $id, string $email, string $bio = null) {
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

/*
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
