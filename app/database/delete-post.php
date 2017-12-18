<?php

require __DIR__.'/../autoload.php';

if (isset($_POST['id'])) {
    $post_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    $query = $pdo-> prepare('DELETE FROM posts WHERE id=:id;');
    if(!$query) {
        die(var_dump($pdo->errorInfo()));
    }
    $query-> bindParam(':id', $post_id, PDO::PARAM_INT);
    $query-> execute();

    redirect('/');
}
