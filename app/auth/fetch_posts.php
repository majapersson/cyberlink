<?php

/*
 * This file is a part of Cyberlink.
 *
 * (c) Maja Persson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../autoload.php';

if (isset($_POST['search'])) {
    $search = filter_var($_POST['search'], FILTER_SANITIZE_STRING);
    $posts = searchPosts($pdo, $search);
    foreach ($posts as $index => $post) {
        $posts[$index]['comments'] = count(getCommentTree($pdo, $post['id']));
    }
    echo json_encode($posts);
    exit;
}

if (!isset($_POST['page'])) {
    echo countPosts($pdo);
    exit;
}

if (isset($_POST['post_id'])) {
    $post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
    return json_encode(getPost($pdo, $post_id));
    exit;
}

if (isset($_POST['user_id'])) {
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);

    $posts = getUserPosts($pdo, $user_id, 5, $page);
    foreach ($posts as $index => $post) {
        $comments = count(getCommentTree($pdo, $post['id']));
        $posts[$index]['comments'] = $comments;
    }
    echo json_encode($posts);
    exit;
}

if (isset($_POST['page'])) {
    $page = filter_var($_POST['page'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $page = 0;
}

$posts['data'] = getPosts($pdo, $page);

if (isset($_POST['post'])) {
    $post_id = filter_var($_POST['post'], FILTER_SANITIZE_NUMBER_INT);
    $post = getPost($pdo, $post_id);
    $index = array_search($post_id, array_column($posts['data'], 'id'));
    array_splice($posts['data'], $index, 1);
    array_unshift($posts['data'], $post);
}

foreach ($posts['data'] as $index => $post) {
    $comments = count(getCommentTree($pdo, $post['id']));
    $posts['data'][$index]['comments'] = $comments;
}

$posts['total'] = countPosts($pdo);

echo json_encode($posts);
