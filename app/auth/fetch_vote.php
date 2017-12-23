<?php

require __DIR__.'/../autoload.php';

if (isset($_SESSION['user'])) {
    if (isset($_POST['vote'])){
        $post_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id = $_SESSION['user']['id'];
        $vote = filter_var($_POST['vote'], FILTER_SANITIZE_NUMBER_INT);

        $user_vote = checkVote($pdo, $post_id, $user_id);

        if (!$user_vote) {
            setVote($pdo, $post_id, $user_id, $vote);
        } else {
            updateVote($pdo, $post_id, $user_id, $vote);
        }

        echo json_encode(checkVote($pdo, $post_id, $user_id));
    } else if (isset($_POST['post'])){
        $post_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

        echo json_encode(getPost($pdo, $post_id));
    } else {
        $post_id = $_POST['id'];
        if (isset($_SESSION['user']['id'])) {
            $user_id = $_SESSION['user']['id'];
        }

        $vote = checkVote($pdo, $post_id, $user_id);

        header('Content-type: application/json');

        echo json_encode($vote);
    }
} else {
    echo json_encode('Not logged in...');
}
