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

<!-- Modal trigger button -->
<button class="btn btn-danger" type="submit" data-toggle="modal" data-target="#deletePostModal">Delete post</button>


<div class="modal fade" id="deletePostModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Are you sure you want to delete this post and all its comments?</p>
            </div>
            <div class="modal-footer">
                <form action="/app/database/delete_post.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $post['id'] ?>">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Delete post</button>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
<?php require __DIR__.'/views/footer.php'; ?>
