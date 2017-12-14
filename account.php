<?php
require __DIR__.'/views/header.php';

$user = getUser($pdo, $_SESSION['user']['id']);
unset($user['password']);

?>

<article>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <h3><?php echo $user['username']; ?></h3>
                <?php if (isset($user['bio'])): ?>
                        <p><?php echo $user['bio']; ?></p>
                <?php endif; ?>
                <a href="update.php">
                    <button type="button" name="button" class="btn btn-primary">Update profile</button>
                </a>
            </div>
            <div class="col-4">
                <?php if (isset($user['image_url'])): ?>
                    <img src="/avatars/<?php echo $user['image_url'] ?>" width="200px">
                <?php endif; ?>
            </div>
        </div>
    </div>

</article>

<?php require __DIR__.'/views/footer.php'; ?>
