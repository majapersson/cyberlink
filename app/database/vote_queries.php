<?php
/**
 * Checks if user has already voted on specific post
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $user_id
 *
 * @return array $user_vote
 */

function checkVote($pdo, $post_id, $user_id) {
    $query = $pdo-> prepare('SELECT * from votes WHERE post_id=:post_id AND user_id=:user_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> execute();

    $user_vote = $query->fetch(PDO::FETCH_ASSOC);

    return $user_vote;
}

/**
 * Inserts new vote into votes table
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $user_id
 * @param int $vote
 *
 * @return void
 */

function setVote($pdo, $post_id, $user_id, $vote) {
    $query = $pdo-> prepare('INSERT INTO votes (post_id, user_id, vote) VALUES (:post_id, :user_id, :vote);');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> bindParam(':vote', $vote, PDO::PARAM_INT);
    $query-> execute();
}

/**
 * Updates existing vote in votes table
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $user_id
 * @param int $vote
 *
 * @return void
 */

function updateVote($pdo, $post_id, $user_id, $vote) {
    $query = $pdo-> prepare('UPDATE votes SET vote=:vote WHERE post_id=:post_id AND user_id=:user_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> bindParam(':vote', $vote, PDO::PARAM_INT);
    $query-> execute();
}
