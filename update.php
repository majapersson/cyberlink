<?php
require __DIR__.'/views/header.php';
$user = getUser($pdo, $_SESSION['user']['id']);
unset($user['password']);

if (isset($_POST['email'], $_POST['bio'])) {
    $user = updateUser($pdo, $user['id'], $_POST['email'], $_POST['bio']);
}

if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    updateImage($pdo, $image, $user);
}
?>

<form action="update.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" type="text" name="username" value="<?php echo $user['username']; ?>" disabled>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input class="form-control" type="email" name="email" value="<?php echo $user['email']; ?>">
    </div>

    <?php if (isset($user['image_url'])): ?>
        <div class="form-group">
            <img src="/avatars/<?php echo $user['image_url']; ?>">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <input type="file" name="image">
    </div>

    <div class="form-group">
        <label for="bio">Biography</label>
        <textarea class="form-control" type="text" name="bio"><?php echo $user['bio']; ?></textarea>
    </div>

    <button class="btn btn-primary" type="submit">Update info</button>
</form>

<?php require __DIR__.'/views/footer.php'; ?>
