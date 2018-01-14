<?php
/**
 * Gets all users from users table
 *
 * @param PDO $pdo
 *
 * @return array
 */

function getUsers(PDO $pdo): array {
    $query = $pdo-> query('SELECT id, username, email, password FROM users;');
    return $query-> fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Gets user array from database based on user id
 *
 * @param PDO $pdo
 * @param int $id
 *
 * @return array $user
 */

function getUser(PDO $pdo, int $id): array {
    // Get user from ID
    $query = $pdo->prepare('SELECT * FROM users WHERE id=:id;');
    if(!$query){
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $id, PDO::PARAM_INT);
    $query-> execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

/**
 *  Saves new user info to database
 *
 * @param PDO $pdo
 * @param string $username
 * @param string $email
 * @param string $password
 *
 * @return void
 */

function setUser(PDO $pdo, string $username, string $email, string $password) {
    $query = $pdo-> prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password);');

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
 *
 * @return array $user
 */

function updateInfo (PDO $pdo, int $id, string $email, string $bio = null): array {
    $query = $pdo-> prepare('UPDATE users SET email=:email, bio=:bio WHERE id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $id, PDO::PARAM_INT);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> bindParam(':bio', $bio, PDO::PARAM_STR);
    $query-> execute();

    // Get updated info
    $user = getUser($pdo, $id);

    return $user;
}

/**
 * Updates user avatar filepath in database and saves image in avatar folder
 *
 * @param PDO $pdo
 * @param array $image
 * @param array $user
 *
 * @return void
 */

function updateImage (PDO $pdo, array $image, array $user) {
    $file = pathinfo($image['name']);
    $filename = $user['username'].'.'.$file['extension'];

    $thumbnail = new Imagick($image['tmp_name']);
    $thumbnail->thumbnailImage(300, 300, true);
    $thumbnail->writeImage(__DIR__.'/../../assets/avatars/'.$filename);

    $thumbnail = new Imagick($image['tmp_name']);
    $thumbnail->thumbnailImage(25, 25, true);
    $thumbnail->writeImage(__DIR__.'/../../assets/avatars/thumbnails/'.$filename);

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
 *
 * @return void
 */

function updatePassword (PDO $pdo, string $new_password, int $user_id) {
    $query = $pdo-> prepare('UPDATE users SET password=:password WHERE id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $password = password_hash($new_password, PASSWORD_DEFAULT);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> bindParam(':id', $user_id, PDO::PARAM_INT);
    $query-> execute();
}
