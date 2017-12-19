<?php
/**
* Gets all posts from post table
*
* @param PDO $pdo
*
* @return array $posts
*/

function getPosts($pdo) {
    $query = $pdo-> query('SELECT posts.*, users.username, votes.score FROM posts JOIN users ON posts.author_id=users.id JOIN votes ON posts.id=votes.post_id ORDER BY timestamp desc;');
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
    $query = $pdo-> query('SELECT posts.*, votes.score from posts JOIN votes ON posts.id=votes.post_id WHERE id=:id;');
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

/**
* Deletes post from database
*
* @param PDO $pdo
* @param int $post_id
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
* @param int $direction
*/

function updateScore($pdo, $post_id, $direction) {
    $post = getPost($pdo, $post_id);
    $post['score'] += $direction;

    $query = $pdo-> prepare('UPDATE votes SET score=:score WHERE post_id=:id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':score', $post['score'], PDO::PARAM_INT);
    $query-> bindParam(':id', $post_id, PDO::PARAM_INT);
    $query-> execute();
}

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
    $query = $pdo-> prepare('SELECT * from user_votes WHERE post_id=:post_id AND user_id=:user_id;');
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
 * Inserts new vote into user_votes table
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $user_id
 * @param int $direction
 */

function setVote($pdo, $post_id, $user_id, $direction) {
    $query = $pdo-> prepare('INSERT INTO user_votes (post_id, user_id, direction) VALUES (:post_id, :user_id, :dir);');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> bindParam(':dir', $direction, PDO::PARAM_INT);
    $query-> execute();
}

/**
 * Updates existing vote in user_votes table
 *
 * @param PDO $pdo
 * @param int $post_id
 * @param int $user_id
 * @param int $direction
 */

function updateVote($pdo, $post_id, $user_id, $direction) {
    $query = $pdo-> prepare('UPDATE user_votes SET direction=:dir WHERE post_id=:post_id AND user_id=:user_id;');
    if (!$query) {
        die(var_dump($pdo->errorInfo()));
    }

    $query-> bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $query-> bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query-> bindParam(':dir', $direction, PDO::PARAM_INT);
    $query-> execute();
}
