<!-- Start comments -->
<?php
$comments = getComments($pdo, $post['id']);

if (isset($comments)):
    printComments($pdo, $comments, $post);
endif;
?>
<!-- End comments -->

<?php
// If user is logged in
if (isset($user['id'])): ?>
    <button class="btn btn-primary" type="button" name="comment">Comment</button>
    <form class="d-none" action="/app/auth/comment.php" method="post">
        <input type="text" name="post_id" value="<?php echo $post['id'] ?>" hidden>
        <textarea class="form-control" name="comment" rows="5" cols="40"></textarea>
        <button class="btn btn-primary" type="submit">Comment</button>
    </form>
<?php endif; ?>
