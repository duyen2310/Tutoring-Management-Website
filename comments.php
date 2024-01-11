<?php
include 'components/header.php';
$confirmation = "";
$error = "";
$id = $cms->getSession()->id;

if (isset($_GET['lpId'])) {
    $lpId = $_GET['lpId'];
    // getting all of the comments for the lp specified in the url
    $lp = $cms->getLp()->get($lpId);
    $comments = $cms->getComment()->getForLp($lpId);
}
if (isset($_POST['add-comment'])) {
    // handling the post of the comment with (cant be more than 500)
    $commentText = $_POST['comment-text'];
    if (empty($commentText)) {
        $error = 'Comment can\'t be empty';
    } elseif (strlen($commentText) > 500) {
        $error = 'Comment can\'t be more than 500 characters';
    } else {
        $confirmation = 'Comment posted successfully';
        // adding the comment to the database using the create method specified in Comment.php
        $cms->getComment()->create([$commentText, $lpId, $id]);
        header("Location: comments.php?lpId=". $lpId);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lp['title']) ?>'s comments</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
</head>

<body>
    <section class="base-section comments">
        <form action="" method="POST">
            <div>
                <label for="comment-text ">Add a comment:</label>
                <textarea name="comment-text" id="comment-text" cols="30" rows="10"></textarea>
            </div>
            <input type="submit" name="add-comment" ; value="Post comment">
            <p class="valid-text text-align"><?php echo $confirmation ?></p>
            <p class="error"><?php echo $error ?></p>
        </form>
    </section>
    <section class="main-section">
        <?php
        foreach ($comments as $comment) {
            // for each comment we display content about it
            echo '<div class="comment-div">';
            echo '<h2>' . htmlspecialchars($comment['username']) . '</h2>';
            echo '<img src="' . (!empty($comment['image_url']) ? $comment['image_url'] : 'img/default.png') . '">';
            echo '<p class="comment-text">' . htmlspecialchars($comment['text']) . '</p>';
            echo '<p class="comment-posted">Posted on: ' . htmlspecialchars($comment['date_posted']) . '</p>';
            // if the user logged in (we get that from the session) is the same as that of the one that posted the section
            // we show the form to delete the comment
            if ($id == $comment['user_id']) {
                echo '<form action="" method="post">';
                echo '<input type="hidden" name="comment_id" value="' . $comment['id'] . '">';
                echo '<input class="comment-btn btn1" type="submit" value="Delete">';
                echo '</form>';
            }
            echo '</div>';
            // handling the delete post in here so we can access commentId more easily instead of using javascript
            // (however in lp.php we have also used javascript to show multiple ways this can be done)
            if(isset($_POST['comment_id'])){
                $commentId = $_POST['comment_id'];
                if ($id == $comment['user_id']) {
                    $cms->getComment()->delete($commentId);
                }
            
            }
        }
        ?>
    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>