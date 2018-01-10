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

<article class="d-flex flex-row pb-5">
        <?php if (isset($user)): ?>
            <a href="new_post.php" class="btn btn-primary">New post</a>
        <?php endif; ?>
        <input class="form-control w-25 justify-end" type="text" name="search" placeholder="Search posts...">
</article>
<section class="d-flex flex-column">
</section>

<script type="text/javascript" src="../assets/scripts/load_posts.js"></script>
<?php require __DIR__.'/views/footer.php'; ?>
