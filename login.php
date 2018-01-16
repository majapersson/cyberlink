<?php require __DIR__.'/views/header.php'; ?>

<section>
  <h2>Login</h2>

  <?php if (isset($_SESSION['errors']['login'])): ?>
    <div class="alert alert-danger w-50">
      <?php echo $_SESSION['errors']['login']; ?>
    </div>
    <?php unset($_SESSION['errors']['login']); ?>
  <?php endif; ?>

  <form action="app/auth/login.php" method="post">
    <div class="form-group w-50">
      <label for="username">Username/email</label>
      <input class="form-control" type="text" name="username" placeholder="name@mail.com" required>
      <small class="form-text text-muted">Please provide your username or email address.</small>
    </div><!-- /form-group -->

    <div class="form-group w-50">
      <label for="password">Password</label>
      <input class="form-control" type="password" name="password" required>
      <small class="form-text text-muted">Please provide your password (passphrase).</small>
    </div><!-- /form-group -->

    <button type="submit" class="btn btn-info">Login</button>
    <a href="reset_password.php"><small>Forgot your password?</small></a>
  </form>
</section>

<?php require __DIR__.'/views/footer.php'; ?>
