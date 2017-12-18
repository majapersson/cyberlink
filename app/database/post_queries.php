<?php
/**
* Gets all posts from post table
*
* @param PDO $pdo
*
* @return array $posts
*/

function getPosts($pdo) {
    $query = $pdo-> query('SELECT posts.*, users.username FROM posts JOIN users ON posts.author_id=users.id ORDER BY timestamp desc;');
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
    return $posts;
}

/**
* Gets post based on post id
*
* @param PDO $pdo
* @param int $post_id
*
* @return array $post
*/

function getPost($pdo, $post_id) {
    $query = $pdo-> query('SELECT * from posts WHERE id=:id;');
    if(!$query) {
        die(var_dump($pod->errorInfo()));
    }
    $query-> bindParam(':id', $post_id, PDO::PARAM_INT);
    $query-> execute();
    $post = $query-> fetch(PDO::FETCH_ASSOC);
    return $post;
}

/**
* Inserts new post in database
*
* @param PDO $pdo
* @param string $title
* @param string $url
* @param string $content (optional)
*/

function setPost($pdo, $title, $url, $content = null) {
    $timestamp = time();

    $query = $pdo-> prepare('INSERT INTO posts (author_id, title, url, timestamp, content) VALUES (:id, :title, :url, :timestamp, :content);');
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
