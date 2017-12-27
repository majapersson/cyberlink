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
    if (isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    foreach ($comments as $comment): ?>
        <div class="card m-2">
            <div class="card-body" data-id=<?php echo $comment['id'] ?> id=<?php echo $comment['id'] ?>>
                <?php if ($comment['user_id'] !== '0'): ?>
                    <a href="account.php/?id=<?php echo $comment['user_id']; ?>"><?php echo $comment['username']; ?></a>
                <?php else: ?>
                    [deleted]
                <?php endif; ?>
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

                <!-- Form class="comment" -->

                <p><?php echo $comment['content']; ?></p>

                <?php
                // Reply stuff
                if (isset($user)): ?>
                    <button class="btn badge badge-primary" name="reply">Reply</button>
                <?php endif; ?>

                <!-- Form class="reply" -->

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
