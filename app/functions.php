<?php

declare(strict_types=1);

if (!function_exists('redirect')) {
    /**
     * Redirect the user to given path.
     *
     * @param string $path
     *
     * @return void
     */
    function redirect(string $path)
    {
        header("Location: ${path}");
        exit;
    }
}

/**
 * Loops through and prints out comments
 * If replies exists, also prints out replies
 *
 * @param PDO $pdo
 * @param array $comments
 * @param array $post
 *
 * @return void
 */

function printComments($pdo, $comments, $post) {
    $user = $_SESSION['user'];
    foreach ($comments as $comment): ?>
        <div class="card m-2">
            <div class="card-body">
                <a href="account.php/?id=<?php echo $comment['user_id']; ?>"><?php echo $comment['username']; ?></a>
                <small><?php echo date('Y-m-d H:i', (int) $comment['timestamp']); ?></small>

                <?php
                // If logged in user is the same as author
                if (isset($user) && $comment['user_id'] === $user['id']): ?>
                    <button class="btn badge badge-primary" name="edit" type="submit">Edit</button>
                    <form action="/../app/auth/comment.php" method="post" class="d-inline">
                        <input name="comment_id" value="<?php echo $comment['id'] ?>" hidden>
                        <button class="btn badge badge-danger" name="delete" type="submit">Delete</button>
                    </form>
                <?php endif; ?>

                <form class="comment d-none" action="/../app/auth/comment.php" method="post">
                    <input name="comment_id" value="<?php echo $comment['id'] ?>" hidden>
                    <textarea class="form-control" name="content" rows="4" cols="80"><?php echo $comment['content'] ?></textarea>
                    <button class="btn btn-primary" name="edit" type="submit">Save</button>
                </form>

                <p><?php echo $comment['content']; ?></p>

                <?php
                // Reply stuff
                if (isset($user)): ?>
                    <button class="btn badge badge-primary" name="reply">Reply</button>
                <?php endif; ?>
                <form class="reply d-none" action="/../app/auth/comment.php" method="post">
                    <input name="post_id" value="<?php echo $post['id'] ?>" hidden>
                    <input name="reply_id" value="<?php echo $comment['id'] ?>" hidden>
                    <textarea class="form-control" name="content" rows="4" cols="80"></textarea>
                    <button class="btn btn-primary" type="submit">Reply</button>
                </form>
                <?php
                // If there are replies
                $replies = getReplies($pdo, $comment['id']);
                if (isset($replies)) {
                    printComments($pdo, $replies, $post);
                } ?>
            </div>
        </div>
    <?php endforeach;
} ?>
