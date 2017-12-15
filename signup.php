<?php require __DIR__.'/views/header.php'; ?>

<article>
    <h1>Sign up</h1>
    <form action="/app/auth/signup.php" method="post">
        <?php if (isset($_SESSION['errors']['user'])): ?>
            <?php foreach($_SESSION['errors']['user'] as $error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['errors']['user']); ?>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" type="text" name="username" required>
            <small class="form-text text-muted">Choose a username.</small>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" placeholder="name@mail.com" required>
            <small class="form-text text-muted">Please provide your email.</small>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" type="password" name="password" required>
            <small class="form-text text-muted">Choose a password.</small>
        </div>

        <button type="submit" class="btn btn-primary">Create account</button>
    </form>
</article>

<?php require __DIR__.'/views/footer.php'; ?>
