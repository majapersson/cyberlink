<?php
/**
 * Gets all posts from post table
 *
 * @param PDO $pdo
 *
 * @return array
 */

function getPosts($pdo) {
    $query = $pdo-> query('SELECT posts.*, users.username, (SELECT sum(vote) FROM votes WHERE posts.id=votes.post_id) AS score FROM posts JOIN votes ON posts.id=votes.post_id JOIN users ON posts.user_id=users.id GROUP BY posts.id ORDER BY timestamp desc;');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Gets post based on post id
 *
 * @param PDO $pdo
 * @param int $post_id
 *
 * @return array
 */

function getPost($pdo, $post_id) {
    $query = $pdo-> query('SELECT posts.*, (SELECT sum(vote) FROM votes WHERE posts.id=votes.post_id) AS score FROM posts JOIN votes ON posts.id=votes.post_id WHERE posts.id=:id GROUP BY posts.id;');
    if(!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $post_id, PDO::PARAM_INT);
    $query-> execute();
    return $query-> fetch(PDO::FETCH_ASSOC);
}

/**
 * Gets posts from specific user
 *
 * @param PDO $pdo
 * @param int $user_id
 *
 * @return array
 */

 function getUserPosts($pdo, $user_id) {
     $query = $pdo-> prepare('SELECT * FROM posts WHERE user_id=:user_id;');
     if (!$query) {
         die(var_dump($pdo->errorInfo()));
     }

     $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
     $query-> execute();

      return $query-> fetchAll(PDO::FETCH_ASSOC);
 }

/**
 * Inserts new post in database
 *
 * @param PDO $pdo
 * @param string $title
 * @param string $url
 * @param string $content (optional)
 *
 * @return void
 */

function setPost($pdo, $title, $url, $content = null) {
    $timestamp = time();

    $query = $pdo-> prepare('INSERT INTO posts (user_id, title, url, timestamp, content) VALUES (:id, :title, :url, :timestamp, :content);');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':id', $_SESSION['user']['id'], PDO::PARAM_INT);
    $query-> bindParam(':title', $title, PDO::PARAM_STR);
    $query-> bindParam(':url', $url, PDO::PARAM_STR);
    $query-> bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
    $query-> bindParam(':content', $content, PDO::PARAM_STR);
    $query-> execute();
}

/**
 * Updates existing post
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param string $title
 * @param string $url
 * @param string $content (optional)
 *
 * @return void
 */

function updatePost($pdo, $post_id, $title, $url, $content = null) {
    $timestamp = time();

    $query = $pdo-> prepare('UPDATE posts SET title=:title, url=:url, timestamp=:timestamp, content=:content WHERE id=:post_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':title', $title, PDO::PARAM_STR);
    $query-> bindParam(':url', $url, PDO::PARAM_STR);
    $query-> bindParam(':timestamp', $timestamp, PDO::PARAM_INT);
    $query-> bindParam(':content', $content, PDO::PARAM_STR);
    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> execute();
}

/**
 * Deletes post from database
 *
 * @param PDO $pdo
 * @param int $post_id
 *
 * @return void
 */

function deletePost($pdo, $post_id) {
    $query = $pdo-> prepare('DELETE FROM posts WHERE id=:id;');
    if(!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $post_id, PDO::PARAM_INT);
    $query-> execute();
}

/**
 * Updates score for specific post
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $vote
 *
 * @return void
 */

function updateScore($pdo, $post_id, $vote) {
    $post = getPost($pdo, $post_id);
    $post['score'] += $vote;

    $query = $pdo-> prepare('UPDATE votes SET score=:score WHERE post_id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':score', $post['score'], PDO::PARAM_INT);
    $query-> bindParam(':id', $post_id, PDO::PARAM_INT);
    $query-> execute();
}
