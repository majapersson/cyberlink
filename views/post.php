<!--Vote icons -->
<?php
if (isset($user)) {
    $user_vote = checkVote($pdo, $post['id'], $user['id']);
    if (!$user_vote) {
        $user_vote = 1;
    }
} else {
    $user_vote = false;
} ?>

<?php if (!$user_vote || $user_vote['direction'] === '1'): ?>
    <i class="fas fa-caret-square-up"></i>
<?php elseif ($user_vote['direction'] === '-1'|| $user_vote === 1): ?>
    <i class="far fa-caret-square-up" data-id=<?php echo $post['id'] ?> data-dir=1></i>
<?php endif; ?>

<?php if (!$user_vote || $user_vote['direction'] === '-1'): ?>
    <i class="fas fa-caret-square-down"></i>
<?php elseif ($user_vote['direction'] === '1' || $user_vote === 1): ?>
    <i class="far fa-caret-square-down" data-id=<?php echo $post['id'] ?> data-dir=-1></i>
<?php endif; ?>
<!-- End vote icons -->

<!-- Actual post -->
<?php echo $post['score'];  ?>
<a href="<?php echo $post['url'] ?>">
    <h3><?php echo $post['title']; ?></h3>
</a>
<a href="account.php/?id=<?php echo $post['author_id'] ?>">
    <h4><?php echo $post['username']; ?></h4>
</a>
<time><?php echo date('Y-m-d H:i', $post['timestamp']); ?></time>
<p><?php echo $post['content'] ?></p>

<?php if (isset($user['id']) && $post['author_id'] === $user['id']): ?>
    <form action="edit-post.php" method="post">
        <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
        <button class="btn btn-primary" type="submit">Edit post</button>
    </form>
<?php endif; ?>
