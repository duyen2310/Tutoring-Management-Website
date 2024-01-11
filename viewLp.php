<?php
include 'components/header.php';
$id = $cms->getSession()->id;
// same as vieLPs however
$author = false;
// we use registered to see if we can allow the user to 
$registered = true;
if ($id == 0) {
    $registered = false;
}
if (isset($_GET['lpid']) && isset($_GET['userId'])) {
    $lpId = $_GET['lpid'];
    $userId = $_GET['userId'];
    // if we dont get an lp from the lp provided in the url then that lp doesnt exist so wew redirect to page 404
    if ($lp = $cms->getLp()->get($lpId) == false) {
        header('Location: page404.php');
        exit();
    }

    if ($userId == $id) {
        $author = true;
    } else {
        $author = false;
    }
    $sections = $cms->getSection()->getAllId($_GET['lpid']);
}
// getting if user has liked or disliked which is used to delete or add a like or dislike later on
$lp = $cms->getLp()->get($lpId);
$liked = $cms->getLike()->get([$lpId, $id]);
$disliked = $cms->getDislike()->get([$lpId, $id]);
$likeDiff= $cms->getLike()->likesToDislikes($lpId)['like_diff'];
// if we click the copylp button the following query is executed and if successfull we are redirected
if(isset($_POST['copy-lp'])){
    $key = $cms->getLp()->createLpCopy($lp['id'],$lp['title'],$id,$lp['language_id'],$lp['c_id'],$lp['user_id'],$lp['description'],$lp['thumbnail']);
    $newLp=$cms->getLp()->getCopy($key);
    header("Location: viewLp.php?lpid=".$newLp['id']."&userId=" . $newLp['user_id']);

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View sections</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/updateViews.js"></script>
    <script defer src="js/like.js"></script>
</head>

<body>
    <script>
        // declaring this inside the html so we can access it from the javascript file used to deal with the like/dislike mechanism
        const lpId = <?php echo json_encode($lpId); ?>;
    </script>
    
    
    <section class="main-section-show">
        <?php  // if the user is registered then they get to like or dislike and copy the lp ;
        if ($registered){?>
        <div class="main-div" id="view-lp-div">
            <button class="upvote-bt"><?php include($liked ? 'img/upvote-full.svg' : 'img/upvote.svg'); ?></button>
            <button class="downvote-btn"><?php include($disliked ? 'img/downvote-full.svg' : 'img/downvote.svg') ?></button>
            <p><?php echo $likeDiff ?></p>
            <?php     echo '<form method=post action=""><input type="submit" class="copy-lp btn1" name="copy-lp" value="Copy Lp"/></form/> ';?>
        </div>
        <h2 class="viewLps-first-h2">Learning sections in the lp <?php echo htmlspecialchars($lp['title']) ?></h2>
        <?php echo '<h2><a class="add-section-btn" href="viewLps.php?user_id=' . $userId . '">See all Lp\'s from this user</a></h2>'; ?><?php }?>


        <?php echo ($author ? '<h2><a class="add-section-btn" href="' . 'addSections.php?lpid=' . $lpId . '&userId=' . $userId . '">Add more sections</a></h2>' : ''); ?>
        <?php
        foreach ($sections as $section) {
            // more or less same concept as with viewLps but to show the sections of one lp instead of the entire thing
            echo '<section class="section-view-wrapper">';
            echo '<img class="section-img" src="' . (isset($section['thumbnail']) ? htmlspecialchars($section['thumbnail']) : 'img/nature.jpg') . '">';
            echo '<div class="section-content-div lp-content">';
            echo '<h2><a class="views-link" href="' . htmlspecialchars($section['url']) . '" data-section-id="' . htmlspecialchars($section['id']) . '">' . htmlspecialchars($section['title']) . '</a></h2>';
            echo '<p class="view-section-description">Description: ' . (isset($section['description']) && !empty($section['description']) ? htmlspecialchars($section['description']) : 'No description given') . '</p>';
            echo '<div class="sec-div1">';
            echo '<p class="">Date Created: ' . htmlspecialchars(date('Y-m-d', strtotime($section['date_created']))) . '</p>';
            echo ($section['date_edited'] !== null) ? '<p class="">Date Edited: ' . htmlspecialchars(date('Y-m-d', strtotime($section['date_edited']))) . '</p>' : '';
            echo '</div>';
            echo '<p class="place-grid-end">Category:' . htmlspecialchars($section['category_name']) . '</p>';
            echo '<p class="section-view-author place-grid-end">Author: ' . htmlspecialchars($section['username']) . '</p>';
            echo '<p class="place-grid-end">Views: ' . htmlspecialchars($section['views']) . '</p>';
            echo $author ? '<a class="edit-button" href="editSection.php?secId=' . htmlspecialchars($section['id']) . '&userId=' . htmlspecialchars($userId) . '">Edit this section</a>' : '';
            echo '</div>';
            echo '</section>';
        }

        ?>
    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>