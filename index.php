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
    <h1><?php echo $config['title']; ?></h1>
    <?php if (isset($user)): ?>
        <h2>Welcome <?php echo $user['username']; ?></h2>
    <?php endif; ?>
    <p>This is the home page.</p>
</article>
<section>
    <h2>Posts</h2>
    <?php if (isset($user)): ?>
        <a href="post.php">
            <button class="btn btn-primary" type="button">New post</button>
        </a>
    <?php endif; ?>

    <!-- Start of posts -->
    <?php foreach($posts as $post): ?>
        <article>
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
        </article>
    <?php endforeach; ?>
    <!-- End of posts -->

</section>

<?php require __DIR__.'/views/footer.php'; ?>
