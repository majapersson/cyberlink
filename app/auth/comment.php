<?php

require __DIR__.'/../autoload.php';

// Get comment by id
if (isset($_POST['id'])) {
    $comment_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $comment = getComment($pdo, $comment_id);
    echo json_encode($comment);

} elseif (isset($_POST['user_id'], $_POST['page'])) {
    // Get user comments
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);

    $comments = getUserComments($pdo, $user_id, $page);
    echo json_encode($comments);
} elseif (!empty($_POST['post_id']) && isset($_POST['content'])) {
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
} elseif (isset($_POST['comment_id'], $_POST['content'])) {
    // Update existing comment
    $comment_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

    $comment = getComment($pdo, $comment_id);
    if ($comment['user_id'] === $_SESSION['user']['id']) {
        updateComment($pdo, $comment_id, $content);
    }
    echo json_encode(getComment($pdo, $comment_id));
} elseif (isset($_POST['delete'])) {
    // Delete existing comment
    $comment_id = filter_var($_POST['comment_id'], FILTER_SANITIZE_NUMBER_INT);
    $comment = getComment($pdo, $comment_id);
    checkDelete($pdo, $comment['id']);
    redirect('/#'.$comment['post_id']);
} elseif (isset($_SESSION['user'])) {
    echo json_encode($_SESSION['user']['id']);
}

// Check if parent comment should also be deleted
function checkDelete($pdo, $comment_id) {
    $comment = getComment($pdo, $comment_id);
    // Check if comment parent is [deleted]
    if (isset($comment['reply_id'])) {
        $parent = getComment($pdo, $comment['reply_id']);
    }
    if ($comment['user_id'] === $_SESSION['user']['id'] || $comment['user_id'] === '0') {
        deleteComment($pdo, $comment_id);
    }
    // If parent comment is [deleted], run the function again to remove it and check its parent
    if ($parent['user_id'] === '0') {
        checkDelete($pdo, $parent['id']);
    }
}
