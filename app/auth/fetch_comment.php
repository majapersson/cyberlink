<?php

require __DIR__.'/../autoload.php';

if (isset($_POST['id'])) {
    $comment_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);

    echo json_encode(getComment($pdo, $comment_id));
}
