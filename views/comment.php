<!-- Start comments -->
<?php
$comments = getComments($pdo, $post['id']);

if (isset($comments)):
    foreach($comments as $comment): ?>
    <div class="card">
        <div class="card-body">
            <a href="account.php/?id=<?php echo $comment['user_id']; ?>"><?php echo $comment['username']; ?></a>
            <small><?php echo date('Y-m-d H:i', $comment['timestamp']); ?></small>

            <?php if (isset($user) && $comment['user_id'] === $user['id']): ?>
                <a href="/" class="badge badge-primary" data-id=<?php echo $comment['id']; ?>>Edit</a>
                <a href="/" class="badge badge-danger" data-id=<?php echo $comment['id']; ?>>Delete</a>
            <?php endif; ?>

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
