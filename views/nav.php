<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#"><?php echo $config['title']; ?></a>

  <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link <?php if ($_SERVER['PHP_SELF'] === '/index.php') {
              echo 'active';
          } ?>" href="/index.php">Home</a>
      </li><!-- /nav-item -->

      <li class="nav-item">
          <a class="nav-link <?php if ($_SERVER['PHP_SELF'] === '/about.php') {
              echo 'active';
          } ?>" href="/signup.php">Sign up</a>
      </li><!-- /nav-item -->

      <li class="nav-item">
          <?php if (isset($_SESSION['user'])): ?>
            <a class="nav-link" href="../app/auth/logout.php">Logout</a>
          <?php else: ?>
            <a class="nav-link <?php if ($_SERVER['PHP_SELF'] === '/login.php') {
                echo 'active';
            } ?>" href="/login.php">Login</a>
          <?php endif; ?>
      </li><!-- /nav-item -->

  </ul><!-- /navbar-nav -->
</nav><!-- /navbar -->
