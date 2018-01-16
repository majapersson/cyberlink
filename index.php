<?php

require __DIR__.'/views/header.php';

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
?>
<main class="d-flex flex-column flex-lg-row col-12">
    <section class="d-flex flex-column col-12 col-lg-9">
    </section>
    <aside class="d-flex flex-column flex-sm-row flex-lg-column col-12 col-lg-3">
        <div class="input-group mb-3 mb-sm-0 mb-lg-3">
            <input class="form-control" type="text" name="search" placeholder="Search posts...">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
        </div>
        <div class="input-group d-flex justify-content-center">
            <?php if (isset($user)): ?>
                <a href="new_post.php" class="btn btn-info">Post new link <i class="fas fa-link ml-2"></i></a>
            <?php endif; ?>
        </div>

    </aside>
</main>

<?php require __DIR__.'/views/footer.php'; ?>
