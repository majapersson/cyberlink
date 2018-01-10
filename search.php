<?php require __DIR__.'/views/header.php' ?>

<article>
    <?php if (isset($_GET['search'])): ?>
        <?php $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING); ?>
    <input type="text" class="form-control" name="search" value="<?php echo $search ?>">
<?php else: ?>
    <input type="text" class="form-control" name="search" placeholder="Search posts...">
<?php endif; ?>
    <section class="d-flex flex-column">

    </section>
</article>


<?php require __DIR__.'/views/footer.php' ?>
