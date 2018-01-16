<?php
    require __DIR__.'/views/header.php';

    if (isset($_POST['post_id'])) {
        $post = getPost($pdo, $_POST['post_id']);
    }


    if (isset($_POST['post_id'], $_POST['title'], $_POST['post_url'])) {
        $id = filter_var($_POST['post_id'], FILTER_SANITIZE_NUMBER_INT);
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $url = filter_var($_POST['post_url'], FILTER_SANITIZE_STRING);

        if (isset($_POST['content'])) {
            $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
        }
        echo gettype($post['user_id']);

        if ($_SESSION['user']['id'] === $post['user_id']) {
            updatePost($pdo, $id, $title, $url, $content);
        }
        redirect("/?post=$id");
    }
?>
<section>
    <form action="edit_post.php" class="mb-2" method="post">
    <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" type="text" name="title" value="<?php echo $post['title'] ?>">
    </div>

    <div class="form-group">
        <label for="post_url">URL</label>
        <input class="form-control" type="text" name="post_url" value="<?php echo $post['url'] ?>">
    </div>

    <div class="form-group">
        <label for="content">Description</label>
        <textarea class="form-control" name="content" rows="8"><?php echo $post['content'] ?></textarea>
    </div>

    <button class="btn btn-info" type="submit">Update post</button>
</form>
<form action="/app/database/delete_post.php" class="form-inline" method="post">
    <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
    <button class="btn btn-danger" type="submit">Delete post</button>
</form>
</section>

<script type="text/javascript">
    // Delete post
    const post_delete = document.querySelector('.btn-danger');
    if (post_delete) {
      post_delete.addEventListener('click', (event) => {
        const reply = confirm('Are you sure?');
        if(!reply) {
          event.preventDefault();
        }
      })
    }
</script>
<?php require __DIR__.'/views/footer.php'; ?>
