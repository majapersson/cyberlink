<?php
require __DIR__.'/views/header.php';
$user = getUser($pdo, $_SESSION['user']['id']);

if (isset($_POST['email'], $_POST['bio'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $bio = filter_var($_POST['bio'], FILTER_SANITIZE_STRING);

    if ($email !== $user['email']) {
        $users = getUsers($pdo);
        foreach($users as $loop_user) {
            if ($loop_user['email'] === $email) {
                $email_error = 'The email is already registered.';
            }
        }
    }


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

if (isset($_POST['new_password'], $_POST['old_password']) && $user['id'] === $_SESSION['user']['id']) {
    if (password_verify($_POST['old_password'], $user['password'])) {
        updatePassword($pdo, $_POST['new_password'], $user['id']);
    } else {
        $error = 'The old password was incorrect.';
    }
}
?>
<section>
    <form action="update.php" class="mb-3" method="post" enctype="multipart/form-data">
        <?php if (isset($user['image_url'])): ?>
            <div class="form-group">
                <img src="/assets/avatars/<?php echo $user['image_url']; ?>">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <input type="file" name="image" required>
        </div>

        <button class="btn btn-info" type="submit">Update image</button>
    </form> <!-- /image form -->


    <form action="update.php" class="mb-3" method="post">
        <div class="form-group w-50">
            <label for="username">Username</label>
            <input class="form-control" type="text" name="username" value="<?php echo $user['username']; ?>" disabled>
        </div>

        <?php if (isset($email_error)): ?>
            <div class="alert alert-danger">
                <?php echo $email_error ?>
            </div>
        <?php unset($email_error);
            endif; ?>
        <div class="form-group w-50">
            <label for="email">Email</label>
            <input class="form-control" type="email" name="email" value="<?php echo $user['email']; ?>">
        </div>

        <div class="form-group w-75">
            <label for="bio">Biography</label>
            <textarea class="form-control" rows="5" name="bio"><?php echo $user['bio']; ?></textarea>
        </div>
        <button class="btn btn-info" type="submit">Update info</button>
    </form> <!-- /info form -->

    <form action="update.php" class="mb-3" method="post">
        <div class="form-group w-50">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
                <?php unset($error);
            endif; ?>
            <div class="form-group w-50">
                <label for="newPassword">New password</label>
                <input class="form-control" type="password" name="new_password">
            </div>
            <div class="form-group w-50">
                <label for="oldPassword">Old password</label>
                <input class="form-control" type="password" name="old_password">
            </div>

            <button class="btn btn-info" type="submit">Update password</button>
        </div>
    </form> <!-- /password form -->

    <!-- Modal trigger button -->
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
        Delete account
    </button>

    <!-- Confirm Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Are you sure you want to delete your account and all posts and comments associated with it?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#passwordModal" data-dismiss="modal">Delete account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="/app/auth/delete.php" method="post">
                        <label for="password">Please enter your password to delete your account:</label>
                        <input class="form-control" type="password" name="password">
                        <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__.'/views/footer.php'; ?>
