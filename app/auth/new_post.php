<?php

require __DIR__.'/../autoload.php';

    if (isset($_POST['title'], $_POST['post_url'])) {
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $url = filter_var($_POST['post_url'], FILTER_SANITIZE_URL);

        if (isset($_POST['content'])) {
            $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
        }

        if (isset($_SESSION['user'])) {
            $post_id = setPost($pdo, $title, $url, $content);
        }
        redirect("/?post=$post_id");
    } else {
        redirect('/');
    }
