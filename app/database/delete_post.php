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

if (isset($_POST['id'])) {
    $post_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $post = getPost($pdo, $post_id);

    if ($_SESSION['user']['id'] === $post['user_id']) {
        $comments = array_reverse(getCommentTree($pdo, $post_id));
        foreach ($comments as $comment) {
            checkDelete($pdo, $comment['id']);
        }
        deleteVotes($pdo, $post_id);
        deletePost($pdo, $post_id);
    }

    redirect('/');
}
