<?php
    require __DIR__.'/views/header.php';

    if (isset($_POST['title'], $_POST['post_url'])) {
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $url = filter_var($_POST['post_url'], FILTER_SANITIZE_STRING);

        if (isset($_POST['content'])) {
            $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
        }

        setPost($pdo, $title, $url, $content);
        redirect('/');
    }
?>

<form action="post.php" method="post">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title">
    </div>

    <div class="form-group">
        <label for="post_url">URL</label>
        <input class="form-control" type="text" name="post_url">
    </div>

    <div class="form-group">
        <label for="content">Description</label>
        <textarea class="form-control" name="content" rows="8"></textarea>
    </div>

    <button class="btn btn-primary" type="submit">Submit</button>

</form>

<?php require __DIR__.'/views/footer.php'; ?>
