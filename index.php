<?php
require __DIR__.'/views/header.php';

$posts = getPosts($pdo);
?>

<article>
    <h1><?php echo $config['title']; ?></h1>
    <?php if (isset($_SESSION['user'])): ?>
        <h2>Welcome <?php echo $_SESSION['user']['username']; ?></h2>
    <?php endif; ?>
    <p>This is the home page.</p>
</article>
<section>
    <h2>Posts</h2>
    <?php if (isset($_SESSION['user'])): ?>
        <a href="post.php"><button class="btn btn-primary" type="button">New post</button></a>
    <?php endif; ?>

    <?php foreach($posts as $post): ?>
        <article>
            <i class="far fa-caret-square-up" data-id=<?php echo $post['id'] ?> data-direction="up"></i>
            <i class="far fa-caret-square-down" data-id=<?php echo $post['id'] ?> data-direction="down"></i>
                <?php echo $post['score'];  ?>
            <a href="<?php echo $post['url'] ?>">
                <h3><?php echo $post['title']; ?></h3>
            </a>
            <a href="account.php/?id=<?php echo $post['author_id'] ?>">
                <h4><?php echo $post['username']; ?></h4>
            </a>
            <time><?php echo date('Y-m-d H:i', $post['timestamp']); ?></time>
            <p><?php echo $post['content'] ?></p>

            <?php if (isset($_SESSION['user']['id']) && $post['author_id'] === $_SESSION['user']['id']): ?>
                <form action="edit-post.php" method="post">
                    <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
                    <button class="btn btn-primary" type="submit">Edit post</button>
                </form>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>

</section>

<?php require __DIR__.'/views/footer.php'; ?>
