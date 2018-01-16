<?php

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
    }

    require __DIR__.'/views/header.php';
?>
<section>
    <h2>New post</h2>
    <form action="new_post.php" method="post">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title">
    </div>

    <div class="form-group">
        <label for="post_url">URL</label>
        <input class="form-control" type="url" name="post_url" placeholder="http://example.com">
    </div>

    <div class="form-group">
        <label for="content">Description</label>
        <textarea class="form-control" name="content" rows="8"></textarea>
    </div>

    <button class="btn btn-info" type="submit">Submit</button>

</form>
</section>


<?php require __DIR__.'/views/footer.php'; ?>
