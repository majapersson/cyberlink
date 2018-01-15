<?php

require __DIR__.'/../autoload.php';

if (isset($_POST['id'])) {
    $post_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $post = getPost($pdo, $post_id);

    if ($_SESSION['user']['id'] === $post['user_id']) {
        $comments = getCommentTree($pdo, $post_id);
        foreach($comments as $comment) {
            checkDelete($pdo, $comment['id']);
        }
        deleteVotes($pdo, $post_id);
        deletePost($pdo, $post_id);
    }

    redirect('/');
}
