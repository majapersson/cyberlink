<?php
require __DIR__.'/views/header.php';
if (isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $user = getUser($pdo, $id);
} else {
    $user = getUser($pdo, $_SESSION['user']['id']);
}
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
                <?php if (!isset($id) || isset($_SESSION['user']['id']) && $id === $_SESSION['user']['id']): ?>
                    <a href="/update.php">
                        <button type="button" name="button" class="btn btn-primary">Update profile</button>
                    </a>
                <?php endif; ?>
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
