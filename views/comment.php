<!-- Start comments -->
<?php
$comments = getComments($pdo, $post['id']);

if (isset($comments)):
    foreach($comments as $comment): ?>
    <div class="card m-2">
        <div class="card-body">
            <a href="account.php/?id=<?php echo $comment['user_id']; ?>"><?php echo $comment['username']; ?></a>
            <small><?php echo date('Y-m-d H:i', $comment['timestamp']); ?></small>

            <?php if (isset($user) && $comment['user_id'] === $user['id']): ?>
                <button class="btn badge badge-primary" name="edit" type="submit">Edit</button>
                <form action="/../app/auth/comment.php" method="post" class="d-inline">
                    <input name="comment_id" value="<?php echo $comment['id'] ?>" hidden>
                    <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
                </form>
            <?php endif; ?>
            <form class="comment d-none" action="/../app/auth/comment.php" method="post">
                <input name="comment_id" value="<?php echo $comment['id'] ?>" hidden>
                <textarea class="form-control" name="content" rows="4" cols="80"><?php echo $comment['content'] ?></textarea>
                <button class="btn btn-primary" name="edit" type="submit">Save</button>
            </form>

            <p><?php echo $comment['content']; ?></p>
        </div>
    </div>
<?php
    endforeach;
endif;
?>
<!-- End comments -->

<?php if (isset($user['id'])): ?>
    <button class="btn btn-primary" type="button" name="comment">Comment</button>
    <form class="d-none" action="/app/auth/comment.php" method="post">
        <input type="text" name="post_id" value="<?php echo $post['id'] ?>" hidden>
        <textarea class="form-control" name="comment" rows="5" cols="40"></textarea>
        <button class="btn btn-primary" type="submit">Comment</button>
    </form>
<?php endif; ?>
