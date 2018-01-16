<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container align-items-start">
        <a class="navbar-brand" href="/"><img src="/assets/images/cyberlink-sm.png" class="mr-2"><?php echo $config['title']; ?></a>
        <?php if (!isset($_SESSION['user'])): ?>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <?php endif; ?>

            <?php if (isset($_SESSION['user'])): ?>
                <ul class="navbar-nav justify-end d-flex align-items-center" id="navbar">
                    <?php $user = getUser($pdo, $_SESSION['user']['id']) ?>
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if (isset($user['image_url'])): ?>
                            <img src="/../assets/avatars/thumbnails/<?php echo $user['image_url']; ?>">
                        <?php else: ?>
                            <img src="/../assets/avatars/thumbnails/default.png">
                        <?php endif; ?>
                                <?php echo $user['username'] ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/account.php">Profile</a>
                                <a class="dropdown-item" href="../app/auth/logout.php">Logout</a>
                            </div>
                        </li>
                    <?php elseif ($_SERVER['PHP_SELF'] !== '/login.php'): ?>
                        <div class="collapse navbar-collapse" id="navbarToggle">
                        <ul class="navbar-nav justify-end d-flex flex-column" id="navbar">
                            <div class="row">
                                <div class="col-12">
                                    <li class="nav-item">
                                        <form class="form-inline" action="/app/auth/login.php" method="post">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control mx-1" name="username" placeholder="username/email">
                                            </div>
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control mx-1" name="password" placeholder="password">
                                            </div>
                                            <button type="submit" class="btn btn-outline-info btn-sm mx-1">Login</button>
                                        </form>
                                    </li>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <small class="mr-2"><a href="/signup.php">Sign up</a></small>
                                    <small><a href="/reset_password.php">Reset password</a></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </ul>
                </div>
        </div>
    </nav>
