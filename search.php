<?php require __DIR__.'/views/header.php' ?>

<section class="d-flex flex-column">
    <?php if (isset($_GET['search'])): ?>
        <?php $search = filter_var($_GET['search'], FILTER_SANITIZE_STRING); ?>
        <div class="input-group w-75">
    <input type="text" class="form-control" name="search" value="<?php echo $search ?>">
        <div class="input-group-append">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
    </div>
<?php else: ?>
    <input type="text" class="form-control" name="search" placeholder="Search posts...">
<?php endif; ?>


</section>


<?php require __DIR__.'/views/footer.php' ?>
