<?php require __DIR__.'/views/header.php'; ?>

<section>
    <h2>Reset password</h2>
    <?php if (isset($_SESSION['reset_success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['reset_success'] ?>
        </div>
        <?php unset($_SESSION['reset_success']); ?>
    <?php elseif (isset($_SESSION['reset_fail'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['reset_fail'] ?>
        </div>
        <?php unset($_SESSION['reset_fail']);
        endif; ?>
    <form action="/app/auth/reset.php" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control w-50" type="email" name="email">
            <small class="form-text text-muted">Please enter the email connected to your account</small>
        </div>
        <button class="btn btn-info" type="submit">Reset password</button>
    </form>
</section>

 <?php require __DIR__.'/views/footer.php'; ?>
