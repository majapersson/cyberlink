<!--Vote icons -->
<?php if (!isset($_SESSION['user'])): ?>
    <i class="far fa-caret-square-up"></i>
    <i class="far fa-caret-square-down"></i>
<?php else: ?>
    <i class="far fa-caret-square-up" data-id=<?php echo $post['id'] ?> data-vote=1></i>
    <i class="far fa-caret-square-down" data-id=<?php echo $post['id'] ?> data-vote=-1></i>
<?php endif; ?>
<!-- End vote icons -->

<!-- Actual post -->
<span><?php echo $post['score'];  ?></span>
<a href="<?php echo $post['url'] ?>">
    <h3><?php echo $post['title']; ?></h3>
</a>

<h4>by
    <a href="account.php/?id=<?php echo $post['user_id'] ?>"><?php echo $post['username']; ?></a>
</h4>
<time><?php echo date('Y-m-d H:i', $post['timestamp']); ?></time>
<p><?php echo $post['content'] ?></p>
<!-- End actual post -->

<!-- Edit button -->
<?php if (isset($user['id']) && $post['user_id'] === $user['id']): ?>
    <form action="edit-post.php" method="post" class="d-inline">
        <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
        <button class="btn btn-primary" type="submit">Edit post</button>
    </form>
<?php endif; ?>
<!-- Comment button -->
<?php
if (isset($user['id'])): ?>
    <button class="btn btn-primary" type="button" name="comment">Comment</button>
    <form class="d-none" action="/app/auth/comment.php" method="post">
        <input type="text" name="post_id" value="<?php echo $post['id'] ?>" hidden>
        <textarea class="form-control" name="comment" rows="5" cols="40"></textarea>
        <button class="btn btn-primary" type="submit">Comment</button>
    </form>
<?php endif; ?>

<!-- Start comments -->
<?php
$comments = getComments($pdo, $post['id']);

if (isset($comments)):
    printComments($pdo, $comments, $post);
endif;
?>
<!-- End comments -->
