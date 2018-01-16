<?php
require __DIR__.'/views/header.php';

if (isset($_GET['post'])) {
    $post_id = filter_var($_GET['post'], FILTER_SANITIZE_NUMBER_INT);
    $post = getPost($pdo, $post_id);
} else {
    redirect('/');
}

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
</section>
<?php require __DIR__.'/views/footer.php' ?>
