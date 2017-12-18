<?php

require __DIR__.'/../autoload.php';

$post_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$direction = filter_var($_GET['direction'], FILTER_SANITIZE_STRING);

if ($direction === 'up') {
    updateScore($pdo, $post_id, 1);
} else if ($direction === 'down') {
    updateScore($pdo, $post_id, -1);
}

redirect('/');
