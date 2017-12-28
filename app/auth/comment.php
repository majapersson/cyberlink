<?php

require __DIR__.'/../autoload.php';

// Get comment by id
if (isset($_POST['id'])) {
    $comment_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    echo json_encode(getComment($pdo, $comment_id));
}

// Get reply by id
if (isset($_POST['reply_id'])) {
    $reply_id = filter_var($_POST['reply_id'], FILTER_SANITIZE_NUMBER_INT);

    echo json_encode(getReplies($pdo, $reply_id));
}

// Insert new comment in database
if (isset($_POST['post_id'], $_POST['comment'])) {
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

    if (!empty($content)){
        setComment($pdo, $post_id, $_SESSION['user']['id'], $content);
    }
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
    echo json_encode(getComment($pdo, $comment_id));
}

// Insert comment reply
if (isset($_POST['reply_id'], $_POST['content'])) {
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $reply_id = filter_var($_POST['reply_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($content) && isset($post_id, $reply_id, $_SESSION['user'])) {
        setComment($pdo, $post_id, $_SESSION['user']['id'], $content, $reply_id);
    }
}
