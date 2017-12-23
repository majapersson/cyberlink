<?php

/**
* TO DO:
*
* - Make parameters into arrays (opt)
*
* - Gör om comment forms till createElement?
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
                    <?php require __DIR__.'/views/post.php'; ?>

                    <?php require __DIR__.'/views/comment.php'; ?>
                </div>
            </article>
        <?php endforeach; ?>
        <!-- End of posts -->
    </div>
</section>

<?php require __DIR__.'/views/footer.php'; ?>
