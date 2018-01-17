<?php

require __DIR__.'/../autoload.php';

if (isset($_POST['user_id'], $_POST['password'])) {
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $user = getUser($pdo, $user_id);

    if (password_verify($password, $user['password'])) {
        $posts = getUserPosts($pdo, $user_id);
        $comments = array_reverse(getUserComments($pdo, $user_id));
        foreach($comments as $comment) {
            checkDelete($pdo, $comment['id']);
        }
        foreach ($posts as $post) {
            deletePost($pdo, $post['id']);
        }
        deleteUserVotes($pdo, $user_id);
        deleteUser($pdo, $user_id);

        $image_path = __DIR__.'/../../assets/avatars/'.$user['image_url'];
        $thumb_path = __DIR__.'/../../assets/avatars/thumbnails/'.$user['image_url'];
        if (file_exists($image_path) && file_exists($thumb_path)) {
            unlink($image_path);
            unlink($thumb_path);
        }

        unset($_SESSION['user']);
        redirect('/');
    } else {
        redirect('/update.php');
    }
}
