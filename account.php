<?php
require __DIR__.'/views/header.php';

$user = getUser($pdo, $_SESSION['user']['id']);
unset($user['password']);

?>

<article>
  <h2>My Account</h2>
  <h3>Username: <?php echo $user['username']; ?></h3>
  <h3>Email: <?php echo $user['email']; ?></h3>
  <?php if (isset($user['bio'])): ?>
    <div class="border border-secondary rounded">
      <p><?php echo $user['bio']; ?></p>
    </div>
  <?php endif; ?>
  <a href="update.php">
  <button type="button" name="button" class="btn btn-primary">Update profile</button>
  </a>

</article>

<?php require __DIR__.'/views/footer.php'; ?>
