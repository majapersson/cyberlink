<?php
require __DIR__.'/views/header.php';
$user = getUser($pdo, $_SESSION['user']['id']);

if (isset($_POST['email'], $_POST['bio'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $bio = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);

    if ($user['id'] === $_SESSION['user']['id']) {
        $user = updateInfo($pdo, $user['id'], $email, $bio);
    }
}

if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    if ($user['id'] === $_SESSION['user']['id']) {
        updateImage($pdo, $image, $user);
        $user = getUser($pdo, $user['id']);
    }
}

if (isset($_POST['newPassword'], $_POST['oldPassword']) && $user['id'] === $_SESSION['user']['id']) {
    if (password_verify($_POST['oldPassword'], $user['password'])) {
        updatePassword($pdo, $_POST['newPassword'], $user['id']);
    } else {
        $error = 'The old password was incorrect.';
    }
}
?>
<form action="update.php" method="post" enctype="multipart/form-data">
    <?php if (isset($user['image_url'])): ?>
        <div class="form-group">
            <img src="/avatars/<?php echo $user['image_url']; ?>" width="200px">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <input type="file" name="image">
    </div>

    <button class="btn btn-primary" type="submit">Update image</button>
</form> <!-- /image form -->


<form action="update.php" method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" type="text" name="username" value="<?php echo $user['username']; ?>" disabled>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input class="form-control" type="email" name="email" value="<?php echo $user['email']; ?>">
    </div>

    <div class="form-group">
        <label for="bio">Biography</label>
        <textarea class="form-control" rows="5" name="bio"><?php echo $user['bio']; ?></textarea>
    </div>
    <button class="btn btn-primary" type="submit">Update info</button>
</form> <!-- /info form -->

<form action="update.php" method="post">
    <div class="form-group">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
            <?php unset($error);
        endif; ?>
        <div class="form-group">
            <label for="newPassword">New password</label>
            <input class="form-control" type="password" name="newPassword">
        </div>
        <div class="form-group">
            <label for="oldPassword">Old password</label>
            <input class="form-control" type="password" name="oldPassword">
        </div>

        <button class="btn btn-primary" type="submit">Update password</button>
    </div>
</form> <!-- /password form -->


<?php require __DIR__.'/views/footer.php'; ?>
