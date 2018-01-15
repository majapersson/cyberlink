<?php

require __DIR__.'/../autoload.php';

// Get comment by id
if (isset($_POST['id'])) {
    $comment_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $comment = getComment($pdo, $comment_id);
    echo json_encode($comment);
    exit;
}

if (isset($_POST['user_id'], $_POST['page'])) {
    // Get user comments
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);

    $comments = getUserComments($pdo, $user_id, 5, $page);
    echo json_encode($comments);
    exit;
}

if (!empty($_POST['post_id']) && isset($_POST['content'])) {
    // Insert new comment in database
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

    if (!empty($content)){
        if (isset($_POST['reply_id'])) {
            $reply_id = filter_var($_POST['reply_id'], FILTER_SANITIZE_NUMBER_INT);
            $comment = setComment($pdo, $post_id, $_SESSION['user']['id'], $content, $reply_id);
        } else {
            $comment = setComment($pdo, $post_id, $_SESSION['user']['id'], $content);
        }
    }

    echo json_encode($comment);
    exit;
}

if (isset($_POST['comment_id'], $_POST['content'])) {
    // Update existing comment
    $comment_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

    $comment = getComment($pdo, $comment_id);
    if ($comment['user_id'] === $_SESSION['user']['id']) {
        updateComment($pdo, $comment_id, $content);
    }
    echo json_encode(getComment($pdo, $comment_id));
    exit;
}

if (isset($_POST['delete'])) {
    // Delete existing comment
    $comment_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    $comment = getComment($pdo, $comment_id);
    checkDelete($pdo, $comment['id']);
    redirect('/post.php?post='.$comment['post_id']);
    exit;
}

if (isset($_SESSION['user'])) {
    echo $_SESSION['user']['id'];
    exit;
}
