<?php
/**
 * Checks if user has already voted on specific post
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $user_id
 *
 * @return array
 */

function checkVote(PDO $pdo, string $post_id, string $user_id) {
    $query = $pdo-> prepare('SELECT * from votes WHERE post_id=:post_id AND user_id=:user_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> execute();

    return $query->fetch(PDO::FETCH_ASSOC);
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

function setVote(PDO $pdo, int $post_id, int $user_id, int $vote) {
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

 function updateVote(PDO $pdo, int $post_id, int $user_id, int $vote) {
    $query = $pdo-> prepare('UPDATE votes SET vote=:vote WHERE post_id=:post_id AND user_id=:user_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> bindParam(':vote', $vote, PDO::PARAM_INT);
    $query-> execute();
}

/**
 * Delete all votes related to a specific post
 *
 * @param PDO $pdo
 * @param int $post_id
 *
 * @return void
 */

function deleteVotes(PDO $pdo, int $post_id) {
    $query = $pdo-> prepare('DELETE FROM votes WHERE post_id=:post_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> execute();
}

/**
 * Delete all votes made by specific user
 *
 * @param PDO $pdo
 * @param int $user_id
 *
 * @return void
 */

function deleteUserVotes(PDO $pdo, int $user_id) {
    $query = $pdo-> prepare('DELETE FROM votes WHERE user_id=:user_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> execute();
}
