<?php

if (isset($_GET['post'])) {
    $post_id = filter_var($_GET['post'], FILTER_SANITIZE_NUMBER_INT);
    $post = getPost($pdo, $post_id);
} else {
    redirect('/');
}

require __DIR__.'/views/header.php';
?>
<section>
    <div class="card m-2">
        <div class="card-body" id=<?php echo $post_id ?>>
            <?php require __DIR__.'/views/post.php' ?>

            <!-- Comment button -->
            <?php
            if (isset($user['id'])): ?>
                <button class="btn btn-info btn-sm" type="button" name="comment">Comment</button>
            <?php endif; ?>

            <!-- Start comments -->
            <?php
            $comments = getComments($pdo, $post['id']);

            if (isset($comments)):
                printComments($pdo, $comments, $post);
            endif;
            ?>
            <!-- End comments -->
        </div>
    </div>

    <div class="modal fade" id="deleteComment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Are you sure you want to delete this comment?</p>
                </div>
                <div class="modal-footer">
                    <form action="/app/auth/comment.php" method="post" name="delete_form">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="delete" class="btn btn-danger">Delete comment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__.'/views/footer.php' ?>
