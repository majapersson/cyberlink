<div class="row">
    <div class="col-1 d-flex flex-column align-items-center">
        <!--Vote icons -->
        <?php if (!isset($_SESSION['user'])): ?>
            <i class="fas fa-sort-up disabled"></i>
            <span><?php echo $post['score'];  ?></span>
            <i class="fas fa-sort-down disabled"></i>
        <?php else: ?>
            <i class="fas fa-sort-up" data-id=<?php echo $post['id'] ?> data-vote=1></i>
            <span><?php echo $post['score'];  ?></span>
            <i class="fas fa-sort-down" data-id=<?php echo $post['id'] ?> data-vote=-1></i>
        <?php endif; ?>
        <!-- End vote icons -->

    </div>
    <div class="col-9">
        <!-- Actual post -->
        <a href="<?php echo $post['url'] ?>">
            <h3><?php echo $post['title']; ?></h3>
        </a>
        <p><?php echo $post['content'] ?></p>
        <small>Submitted by
            <a href="account.php/?id=<?php echo $post['user_id'] ?>"><?php echo $post['username']; ?></a> on
            <time><?php echo date('Y-m-d H:i', $post['timestamp']); ?></time>
        </small>
        <?php if ($_SERVER['PHP_SELF'] !== '/post.php'): ?>
        <a href="/post.php?post=<?php echo $post['id'] ?>"><small class="d-block"><?php echo count(getCommentTree($pdo, $post['id'])) ?> comments</small></a>
        <?php endif; ?>
    </div>
    <div class="col-2 text-right">
        <!-- Edit button -->
        <?php if (isset($user['id']) && $post['user_id'] === $user['id']): ?>
            <form action="edit_post.php" method="post" class="d-inline">
                <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
                <button class="btn btn-primary" type="submit">Edit post</button>
            </form>
        <?php endif; ?>

    </div>
</div>
<!-- End actual post -->
