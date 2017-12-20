<?php

require __DIR__.'/../autoload.php';

$post_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$vote = filter_var($_GET['vote'], FILTER_SANITIZE_NUMBER_INT);

if (isset($_SESSION['user'])){
    // Gets user votes
    $user_vote = checkVote($pdo, $post_id, $_SESSION['user']['id']);

    // If check returns null
    if (!$user_vote) {
        setVote($pdo, $post_id, $_SESSION['user']['id'], $vote);
    } else {
        updateVote($pdo, $post_id, $_SESSION['user']['id'], $vote);
    }
}

redirect('/');
