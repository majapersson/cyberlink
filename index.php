<?php

/**
* TO DO:
*
* - Make parameters into arrays
*/

require __DIR__.'/views/header.php';

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
$posts = getPosts($pdo);
?>

<article>
    <?php if (isset($user)): ?>
        <h2>Welcome <?php echo $user['username']; ?></h2>
    <?php endif; ?>
    <p>This is the home page.</p>
</article>
<section class="card">
    <div class="card-body d-flex flex-column">
        <div class="row justify-content-between p-3">

            <h2>Posts</h2>
            <?php if (isset($user)): ?>
                <a href="post.php" class="btn btn-primary">New post</a>
            <?php endif; ?>
        </div>

        <!-- Start of posts -->
        <?php foreach($posts as $post): ?>
            <article class="card m-1">
                <div class="card-body">

                    <!--Vote icon logic -->
                    <?php if (isset($user)) {
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

                    <!-- Edit button -->
                    <?php if (isset($user['id']) && $post['author_id'] === $user['id']): ?>
                        <form action="edit-post.php" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
                            <button class="btn btn-primary" type="submit">Edit post</button>
                        </form>
                    <?php endif; ?>
                    <?php $comments = getComments($pdo, $post['id']);
                    if (isset($comments)):
                        foreach($comments as $comment): ?>
                        <div class="card">
                            <div class="card-body">
                                <a href="account.php/?id=<?php echo $comment['user_id']; ?>"><?php echo $comment['username']; ?></a>
                                <small><?php echo date('Y-m-d H:i', $comment['timestamp']); ?></small>
                                <p><?php echo $comment['content']; ?></p>
                            </div>
                        </div>
                    <?php endforeach;
                    endif; ?>
                    <!-- Comment button -->
                    <?php if (isset($user['id'])): ?>
                    <button class="btn btn-primary" type="button" name="comment">Comment</button>
                    <form class="d-none" action="/app/auth/comment.php" method="post">
                        <input type="text" name="post_id" value="<?php echo $post['id'] ?>" hidden>
                        <textarea class="form-control" name="comment" rows="5" cols="40"></textarea>
                        <button class="btn btn-primary" type="submit">Comment</button>
                    </form>
                <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
        <!-- End of posts -->
    </div>
</section>

<?php require __DIR__.'/views/footer.php'; ?>
