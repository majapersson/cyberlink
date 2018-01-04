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
    <div class="container card">
        <div class="row card-body">
            <div class="col-8">
                <h3><?php echo $user['username']; ?></h3>
                <?php if (isset($user['bio'])): ?>
                    <p><?php echo $user['bio']; ?></p>
                <?php endif; ?>
                <div class="card">
                    <div class="card-body">
                        <h3>Posts by <?php echo $user['username'] ?></h3>
                        <?php $posts = getUserPosts($pdo, $user['id']);
                        foreach ($posts as $post): ?>
                        <div class="card">
                            <div class="card-body">
                                <h4><a href="<?php echo $post['url'] ?>">
                                    <?php echo $post['title'] ?></h4></a>
                                    <time><?php echo date('Y-m-d H:i', $post['timestamp']); ?></time>
                                <p><?php echo $post['content'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                <?php if (!isset($id) || isset($_SESSION['user']['id']) && $id === $_SESSION['user']['id']): ?>
                    <a href="/update.php">
                        <button type="button" name="button" class="btn btn-primary">Update profile</button>
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-4 text-center">
                <?php if (isset($profile['image_url'])): ?>
                    <img src="/assets/avatars/<?php echo $profile['image_url'] ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>

</article>

<?php require __DIR__.'/views/footer.php'; ?>
