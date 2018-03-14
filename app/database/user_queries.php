<?php

/*
 * This file is a part of Cyberlink.
 *
 * (c) Maja Persson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function getUsers(PDO $pdo): array
{
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

function getUser(PDO $pdo, int $id): array
{
    // Get user from ID
    $query = $pdo->prepare('SELECT * FROM users WHERE id=:id;');
    if (!$query) {
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

function setUser(PDO $pdo, string $username, string $email, string $password)
{
    $filename = 'default.png';

    $query = $pdo-> prepare('INSERT INTO users (username, email, password, image_url) VALUES (:username, :email, :password, :image);');

    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> bindParam(':image', $filename, PDO::PARAM_STR);

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

function updateInfo(PDO $pdo, int $id, string $email, string $bio = null): array
{
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

function updateImage(PDO $pdo, array $image, array $user)
{
    $file = pathinfo($image['name']);
    $filename = $user['username'].'.'.$file['extension'];

    if (isset($user['image_url'])) {
        $image_path = __DIR__.'/../../assets/avatars/'.$user['image_url'];
        $thumb_path = __DIR__.'/../../assets/avatars/thumbnails/'.$user['image_url'];
        if (file_exists($image_path) && file_exists($thumb_path)) {
            unlink($image_path);
            unlink($thumb_path);
        }
    }

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

function updatePassword(PDO $pdo, string $new_password, int $user_id)
{
    $query = $pdo-> prepare('UPDATE users SET password=:password WHERE id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $password = password_hash($new_password, PASSWORD_DEFAULT);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> bindParam(':id', $user_id, PDO::PARAM_INT);
    $query-> execute();
}

/**
* Deletes user from database
*
* @param PDO $pdo
* @param int $user_id
*
* @return void
*/

function deleteUser(PDO $pdo, int $user_id)
{
    $query = $pdo-> prepare('DELETE FROM users WHERE id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $user_id, PDO::PARAM_INT);
    $query-> execute();
}
