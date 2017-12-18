<?php

require __DIR__.'/../autoload.php';

if (isset($_POST['id'])) {
    $post_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    deletePost($pdo, $post_id);

    redirect('/');
}
