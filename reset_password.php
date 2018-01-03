<?php require __DIR__.'/views/header.php'; ?>

<article>
    <h1>Reset password</h1>
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
            <input class="form-control" type="email" name="email">
            <small class="form-text text-muted">Please enter the email connected to your account</small>
        </div>
        <button class="btn btn-primary" type="submit">Reset password</button>
    </form>
</article>

 <?php require __DIR__.'/views/footer.php'; ?>
