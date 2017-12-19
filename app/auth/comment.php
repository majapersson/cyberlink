<?php

require __DIR__.'/../autoload.php';

$post_id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
$content = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

setComment($pdo, $post_id, $_SESSION['user']['id'], $content);

redirect('/');
