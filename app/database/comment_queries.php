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
    $query = $pdo-> prepare('SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id=users.id WHERE post_id=:post_id AND reply_id is null ORDER BY timestamp;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Gets specific comment from database
 *
 * @param PDO $pdo
 * @param int $id
 *
 * @return array
 */

 function getComment($pdo, $id) {
     $query = $pdo-> prepare('SELECT * FROM comments WHERE id=:id;');
     if (!$query) {
         die(var_dump($pdo->errorInfo()));
     }
     $query-> bindParam(':id', $id, PDO::PARAM_INT);
     $query-> execute();
     return $query-> fetch(PDO::FETCH_ASSOC);

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

function setComment($pdo, $post_id, $user_id, $content, $reply_id = null) {
    $timestamp = time();

    $query = $pdo-> prepare('INSERT INTO comments (post_id, user_id, content, timestamp, reply_id) VALUES (:post_id, :user_id, :content, :timestamp, :reply_id);');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> bindParam(':content', $content, PDO::PARAM_STR);
    $query-> bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
    $query-> bindParam(':reply_id', $reply_id, PDO::PARAM_INT);
    $query-> execute();
}

/**
 * Deletes comment from database
 *
 * @param PDO $pdo
 * @param int $comment_id
 *
 * @return void
 */

 function deleteComment($pdo, $comment_id) {
     $query = $pdo-> prepare('DELETE FROM comments WHERE id=:comment_id;');
     if (!$query) {
         die(var_dump($pdo->errorInfo()));
     }
     $query-> bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
     $query-> execute();
 }

 /**
  * Updates existing comment
  *
  * @param PDO $pdo
  * @param int $comment_id
  * @param string $content
  *
  * @return void
  */

  function updateComment($pdo, $comment_id, $content) {
      $timestamp = time();

      $query = $pdo-> prepare('UPDATE comments SET content=:content, timestamp=:timestamp WHERE id=:comment_id;');
      if (!$query) {
          die(var_dump($pdo->errorInfo()));
      }

      $query-> bindParam(':content', $content, PDO::PARAM_STR);
      $query-> bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
      $query-> bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
      $query-> execute();
  }

  /**
   * Gets replies to specific comment, if there are any
   *
   * @param PDO $pdo
   * @param int $comment_id
   *
   * @return array
   */

   function getReplies($pdo, $id) {
       $query = $pdo-> prepare('SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id=users.id WHERE reply_id=:id;');
       if (!$query) {
           die(var_dump($pdo->errorInfo()));
       }
       $query-> bindParam(':id', $id, PDO::PARAM_INT);
       $query-> execute();
       return $query-> fetchAll(PDO::FETCH_ASSOC);
   }
