<?php

require __DIR__.'/../autoload.php';

if (isset($_POST['post_id'], $_POST['comment'])) {
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

    setComment($pdo, $post_id, $_SESSION['user']['id'], $content);

    redirect('/');
}

if (isset($_GET['id'])) {
    $comment_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    if (isset($_GET['del'])) {
        deleteComment($pdo, $comment_id);
        redirect('/');
    }
}
