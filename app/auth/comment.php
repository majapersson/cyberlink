<?php

require __DIR__.'/../autoload.php';

// Insert new comment in database
if (isset($_POST['post_id'], $_POST['comment'])) {
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

    setComment($pdo, $post_id, $_SESSION['user']['id'], $content);

}

// Delete existing comment
if (isset($_POST['delete'])) {
    $comment_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    $comment = getComment($pdo, $comment_id);
    if ($comment['user_id'] === $_SESSION['user']['id']) {
        deleteComment($pdo, $comment_id);
    }
}

// Update existing comment
if (isset($_POST['edit'])) {
    $comment_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

    $comment = getComment($pdo, $comment_id);
    if ($comment['user_id'] === $_SESSION['user']['id']) {
        updateComment($pdo, $comment_id, $content);
    }
}

// Insert comment reply
if (isset($_POST['reply_id'])) {
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $reply_id = filter_var($_POST['reply_id'], FILTER_SANITIZE_NUMBER_INT);


    setComment($pdo, $post_id, $_SESSION['user']['id'], $content, $reply_id);
}

redirect('/');
