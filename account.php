<?php
require __DIR__.'/views/header.php';
if (isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $profile = getUser($pdo, $id);
} else {
    $profile = getUser($pdo, $user['id']);
}
unset($profile['password']);

?>

<article>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <div class="row">
                        <div class="col-8">
                            <h3><?php echo $profile['username']; ?></h3>
                            <?php if (isset($profile['bio'])): ?>
                                <p><?php echo $profile['bio']; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-4 text-right">
                            <?php if (!isset($id) || isset($user['id']) && $id === $user['id']): ?>
                                <a href="/update.php">
                                    <button type="button" name="button" class="btn btn-primary">Update profile</button>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <article class="mb-3">
                        <h3>Posts by <?php echo $profile['username'] ?></h3>
                        <?php $posts = getUserPosts($pdo, $profile['id']);
                        foreach ($posts as $post): ?>

                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-9">
                                        <!-- Actual post -->
                                        <a href="<?php echo $post['url'] ?>">
                                            <h3><?php echo $post['title']; ?></h3>
                                        </a>
                                        <p><?php echo $post['content'] ?></p>
                                        <small>Submitted on
                                            <time><?php echo date('Y-m-d H:i', $post['timestamp']); ?></time>
                                        </small>
                                        <a href="/post.php?post=<?php echo $post['id'] ?>"><small class="d-block"><?php echo count(getCommentTree($pdo, $post['id'])) ?> comments</small></a>
                                    </div> <!-- End col-9 -->

                                    <div class="col-2 text-right">
                                        <!-- Edit button -->
                                        <?php if (isset($user['id']) && $post['user_id'] === $user['id']): ?>
                                            <form action="edit_post.php" method="post" class="d-inline">
                                                <input type="hidden" name="post_id" value="<?php echo $post['id'] ?>">
                                                <button class="btn btn-primary" type="submit">Edit post</button>
                                            </form>
                                        <?php endif; ?>
                                    </div><!-- End col-2 -->
                                </div><!-- End row -->
                            </div><!-- End card-body -->
                        </div><!-- End card -->
                    <?php endforeach; ?>
                </article>
                <article class="mb-3">
                    <h3>Comments by <?php echo $profile['username'] ?></h3>
                    <?php $comments = getUserComments($pdo, $profile['id']); ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="card mb-2">
                            <div class="card-body" data-id=<?php echo $comment['id'] ?>>
                                <div class="row">
                                    <div class="col-8">
                                        <!-- Comment post title -->
                                        <a href="/post.php?post=<?php echo $comment['post_id'] ?>#<?php echo $comment['id'] ?>"><?php echo $comment['title'] ?></a> by <a href="/account.php?id=<?php echo $comment['user_id'] ?>"><?php echo $comment['username'] ?></a>
                                        <!-- Actual comment -->
                                        <p><?php echo $comment['content'] ?></p>
                                        <small>Submitted on <?php echo date('Y-m-d H:i', $comment['timestamp']); ?></small>
                                    </div>
                                    <div class="col-4 text-right">
                                        <!-- Buttons -->
                                        <?php
                                        // If logged in user is the same as author
                                        if (isset($user) && $comment['user_id'] === $user['id']): ?>
                                        <button class="btn badge badge-primary" name="edit" type="submit">Edit</button>
                                        <form class="d-inline" action="/app/auth/comment.php" method="post">
                                            <input type="hidden" name="comment_id" value="<?php echo $comment['id'] ?>">
                                            <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </article>
        </div>
        <div class="col-4 text-center">
            <?php if (isset($profile['image_url'])): ?>
                <img src="/assets/avatars/<?php echo $profile['image_url'] ?>">
            <?php endif; ?>
        </div>
    </div><!-- End row -->
</div><!-- End card-body -->
</div><!-- End card -->

</article>

<?php require __DIR__.'/views/footer.php'; ?>
