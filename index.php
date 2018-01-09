<?php

/**
* TO DO:
*
* - Make parameters into arrays (opt)
*
* - Send forms with fetch to avoid page reload
* - Fix comment buttons on new comment
* - Fix database so that tables correlate
* - Better styling
*
*/

require __DIR__.'/views/header.php';

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
?>

<article>
    <?php if (isset($user)): ?>
        <h2>Welcome <?php echo $user['username']; ?></h2>
    <?php endif; ?>
</article>
<section class="d-flex flex-column">
        <div class="row justify-content-between p-3">

            <h2>Posts</h2>
            <?php if (isset($user)): ?>
                <a href="new_post.php" class="btn btn-primary">New post</a>
            <?php endif; ?>
        </div>

        <!-- Start of posts -->
        <?php foreach($posts as $post): ?>
            <article class="card m-1" id="<?php echo $post['id'] ?>">
                <div class="card-body">
                    <?php require __DIR__.'/views/post.php'; ?>
                </div>
            </article>
        <?php endforeach; ?>
        <!-- End of posts -->
</section>

<script type="text/javascript" src="../assets/scripts/load_posts.js"></script>
<?php require __DIR__.'/views/footer.php'; ?>
