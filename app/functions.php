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

function printComments(PDO $pdo, array $comments, array $post) {
    if (isset($_SESSION['user'])){
        $user = $_SESSION['user'];
    }
    foreach ($comments as $comment): ?>
    <div class="card mt-2 comment" id=<?php echo $comment['id'] ?>>
        <div class="card-body p-2" data-id=<?php echo $comment['id'] ?>>
            <div class="row">
                <div class="col-10">
                    <?php if ($comment['user_id'] !== '0'): ?>
                        <a href="account.php/?id=<?php echo $comment['user_id']; ?>">
                            <?php if (isset($comment['image_url'])): ?>
                                <img src="/../assets/avatars/thumbnails/<?php echo $comment['image_url']; ?>">
                            <?php else: ?>
                                <img src="/../assets/avatars/thumbnails/default.png">
                            <?php endif; ?>
                            <?php echo $comment['username']; ?></a>
                        <?php else: ?>
                            [deleted]
                        <?php endif; ?>
                        <small><?php echo date('Y-m-d H:i', (int) $comment['timestamp']); ?></small>
                    </div>
                    <div class="col-2 text-right">
                        <?php
                        // If logged in user is the same as author
                        if (isset($user) && $comment['user_id'] === $user['id']): ?>
                        <button class="btn badge badge-info outline" name="edit" type="submit">Edit</button>
                        <button class="btn badge badge-danger" data-toggle="modal" data-target="#deleteComment" data-id=<?php echo $comment['id'] ?>>Delete</button>
                    <?php endif; ?>
                </div>
            </div>
            <p><?php echo $comment['content']; ?></p>

            <?php
            // Reply button
            if (isset($user) && $comment['user_id'] !== '0'): ?>
            <button class="btn badge badge-info" name="reply">Reply</button>
        <?php endif; ?>

        <?php
        // If there are replies
        $replies = getReplies($pdo, (int) $comment['id']);
        if (isset($replies)) {
            printComments($pdo, $replies, $post);
        } ?>
    </div>
</div>
<?php endforeach;
}

// Check if parent comment should also be deleted
function checkDelete($pdo, $comment_id) {
    $comment = getComment($pdo, (int) $comment_id);
    // Check if comment parent is [deleted]
    if (isset($comment['reply_id'])) {
        $parent = getComment($pdo, (int) $comment['reply_id']);
    }
    // Checks if user is the same as comment author, post author (for post deletion) or 0
    if ($comment['user_id'] === $_SESSION['user']['id'] || $comment['user_id'] === '0' || $comment['post_author'] === $_SESSION['user']['id']) {
        deleteComment($pdo, (int) $comment_id);
    }
    // If parent comment is [deleted], run the function again to remove it and check its parent
    if (isset($parent) && $parent['user_id'] === '0') {
        checkDelete($pdo, $parent['id']);
    }
}
