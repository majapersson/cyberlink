<?php

/*
 * This file is a part of Cyberlink.
 *
 * (c) Maja Persson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function getComments(PDO $pdo, int $post_id): array
{
    $query = $pdo-> prepare('SELECT comments.*, users.username, users.image_url FROM comments JOIN users ON comments.user_id=users.id WHERE post_id=:post_id AND reply_id is null ORDER BY timestamp desc;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Return all comments for specific post
 *
 * @param PDO $pdo
 * @param int $post_id
 *
 * @return array/boolean
 */

 function getCommentTree(PDO $pdo, int $post_id)
 {
     $query = $pdo-> prepare('SELECT * FROM comments WHERE post_id=:post_id;');
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
 * @return array/boolean
 */

 function getComment(PDO $pdo, int $id)
 {
     $query = $pdo-> prepare('SELECT comments.*, users.username, users.image_url, posts.user_id as post_author FROM comments JOIN users ON comments.user_id=users.id JOIN posts ON comments.post_id=posts.id WHERE comments.id=:id;');
     if (!$query) {
         die(var_dump($pdo->errorInfo()));
     }
     $query-> bindParam(':id', $id, PDO::PARAM_INT);
     $query-> execute();
     return $query-> fetch(PDO::FETCH_ASSOC);
 }

 /**
  * Gets all comments by user
  *
  * @param PDO $pdo
  * @param int $user_id
  *
  * @return array/boolean
  */

  function getUserComments(PDO $pdo, int $user_id, int $limit=null, int $offset=null)
  {
      if (isset($limit, $offset)) {
          $offset = $offset*5;
          $query = $pdo->prepare('SELECT comments.*, posts.title, posts.user_id AS author, users.username FROM comments JOIN posts ON comments.post_id=posts.id JOIN users ON posts.user_id=users.id WHERE comments.user_id=:user_id ORDER BY comments.timestamp desc LIMIT :limit OFFSET :offset;');
          if (!$query) {
              die(var_dump($pdo->errorInfo()));
          }
          $query-> bindParam(':limit', $limit, PDO::PARAM_INT);
          $query-> bindParam(':offset', $offset, PDO::PARAM_INT);
      } else {
          $query = $pdo->prepare('SELECT comments.*, posts.title, posts.user_id AS author, users.username FROM comments JOIN posts ON comments.post_id=posts.id JOIN users ON posts.user_id=users.id WHERE comments.user_id=:user_id ORDER BY comments.timestamp desc;');
          if (!$query) {
              die(var_dump($pdo->errorInfo()));
          }
      }
      $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
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

function setComment(PDO $pdo, int $post_id, int $user_id, string $content, int $reply_id = null)
{
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

    $comment_id = $pdo->lastInsertId();

    return getComment($pdo, $comment_id);
}

/**
 * Deletes comment from database
 *
 * @param PDO $pdo
 * @param int $comment_id
 *
 * @return void
 */

 function deleteComment(PDO $pdo, int $comment_id)
 {
     $replies = getReplies($pdo, $comment_id);
     // If comment doen't have any replies, delete it completely
     if (empty($replies)) {
         $query = $pdo-> prepare('DELETE FROM comments WHERE id=:comment_id;');
         if (!$query) {
             die(var_dump($pdo->errorInfo()));
         }
         $query-> bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
         $query-> execute();
     } else { // Else change content to [deleted]
         $query = $pdo-> prepare('UPDATE comments SET user_id=0, content="[deleted]" WHERE id=:comment_id;');
         if (!$query) {
             die(var_dump($pdo->errorInfo()));
         }
         $query-> bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
         $query-> execute();
     }
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

  function updateComment(PDO $pdo, int $comment_id, string $content)
  {
      $query = $pdo-> prepare('UPDATE comments SET content=:content WHERE id=:comment_id;');
      if (!$query) {
          die(var_dump($pdo->errorInfo()));
      }

      $query-> bindParam(':content', $content, PDO::PARAM_STR);
      $query-> bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
      $query-> execute();
  }

  /**
   * Gets replies to specific comment, if there are any
   *
   * @param PDO $pdo
   * @param int $comment_id
   *
   * @return array/boolean
   */

   function getReplies(PDO $pdo, int $id)
   {
       $query = $pdo-> prepare('SELECT comments.*, users.username, users.image_url FROM comments JOIN users ON comments.user_id=users.id WHERE reply_id=:id;');
       if (!$query) {
           die(var_dump($pdo->errorInfo()));
       }
       $query-> bindParam(':id', $id, PDO::PARAM_INT);
       $query-> execute();
       return $query-> fetchAll(PDO::FETCH_ASSOC);
   }
