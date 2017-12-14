<?php require __DIR__.'/views/header.php'; ?>

<article>
  <h1>Login</h1>

  <?php if (isset($_SESSION['errors']['login'])): ?>
    <div class="alert alert-danger">
      <?php echo $_SESSION['errors']['login']; ?>
    </div>
    <?php unset($_SESSION['errors']['login']); ?>
  <?php endif; ?>

  <form action="app/auth/login.php" method="post">
    <div class="form-group">
      <label for="username">Username/email</label>
      <input class="form-control" type="text" name="username" placeholder="francis@darjeeling.com" required>
      <small class="form-text text-muted">Please provide your username or email address.</small>
    </div><!-- /form-group -->

    <div class="form-group">
      <label for="password">Password</label>
      <input class="form-control" type="password" name="password" required>
      <small class="form-text text-muted">Please provide your password (passphrase).</small>
    </div><!-- /form-group -->

    <button type="submit" class="btn btn-primary">Login</button>
  </form>
</article>

<?php require __DIR__.'/views/footer.php'; ?>
