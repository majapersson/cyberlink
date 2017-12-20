<?php

require __DIR__.'/../autoload.php';


if (isset($_POST['post_id'], $_POST['comment'])) {
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

    setComment($pdo, $post_id, $_SESSION['user']['id'], $content);

    redirect('/');
}

if (isset($_POST['delete'])) {
    $comment_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    $comment = getComment($pdo, $comment_id);
    if ($comment['user_id'] === $_SESSION['user']['id']) {
        deleteComment($pdo, $comment_id);
        redirect('/');
    }
}
