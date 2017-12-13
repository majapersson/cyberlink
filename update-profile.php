<?php
    require __DIR__.'/views/header.php';
    $user = $_SESSION['user'];
?>

<form action="update-profile.php" method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" type="text" name="username" value="<?php echo $user['username']; ?>" disabled>
    </div>
    <div class="form-group">
        <label for="bio">Biography</label>
        <textarea class="form-control" type="text" name="bio" value=""></textarea>
    </div>
</form>

<?php require __DIR__.'/views/footer.php'; ?>
