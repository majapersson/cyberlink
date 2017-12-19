<?php

/**
 * Gets comments for specific post
 *
 * @param PDO $pdo
 * @param int $post_id
 *
 * @return array
 */

function getComments($pdo, $post_id) {
    $query = $pdo-> prepare('SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id=users.id WHERE post_id=:post_id ORDER BY timestamp;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Saves new comment to comment table
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $user_id
 * @param string $content
 *
 * @return void
 */

function setComment($pdo, $post_id, $user_id, $content) {
    $timestamp = time();

    $query = $pdo-> prepare('INSERT INTO comments (post_id, user_id, content, timestamp) VALUES (:post_id, :user_id, :content, :timestamp);');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> bindParam(':content', $content, PDO::PARAM_STR);
    $query-> bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
    $query-> execute();
}
