<?php
include 'components/header.php';
// getting id from the session
$id = $cms->getSession()->id;
$author = false;
$message ="";
// if user_id is set in the url continue else exit
if (isset($_GET['user_id'])) {
    // user id is what we get from the url
    $user_id = $_GET['user_id'];
    // extracting that user
    $user = $cms->getUser()->getOne($user_id);

    if (!$user || !$cms->getLp()->getAllOfUser($user_id)) {
        // Redirect to page404.php if the user doesn't exist or has no LPs

    }
    // if the user id from the session is the same as the one we extract that user is the author and has the ability to see other links later on
    if ($user_id == $id) {
        $author = true;
    }
    // getting all lps from that user
    $myLps = $cms->getLp()->getAllOfUser($user['id']);

} else {
    // Redirect to page404.php if user_id is not set
    header('Location: page404.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View learning paths</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/updateViews.js"></script>
    <script defer src="js/copyurl.js"></script>
</head>

<body>
<section class="hidden delete-section">
    <div id="backdrop">
        <div class="delete-popup" >
            <h1>Are you sure you want to delete this section?</h1>
            <form action="" class="delete-form" method="post">
                <input type="submit" name="delete-form" id="delete-yes" value="Yes" class="delete-btn">
                <button class="delete-btn">No</button>
            </form>
        </div>
    </div>
</section>
    <section class="main-section-show">
         <?php echo '<p class="viewLps-first-p">' . $message . '</p>'; 
         // displaying some dynamic info and also an option for the user to add more lps if he is the author?>
         <h2 class="viewLps-first-h2">Learning Paths from user <?php echo htmlspecialchars($user['username'])?></h2>  
         <?php echo '<h2><a class="add-section-btn" href="profile.php?user_id=' . $user_id . '">Go back to user profile</a></h2>';?>
         <?php echo ($author ? '<h2><a class="add-section-btn" href="' . 'addLp.php?userId='.$user_id.'">Add more lp\'s</a></h2>' : ''); ?>
        <?php
        // itterating through every lp and outputting a bunch of data for each one of them(some of which is embedded into urls and other in images)
        // also here htmlspecialchars() is used to prevent sql injection attack
foreach ($myLps as $lp) {
    // getting all the like differentials so we can display them later on
    $likeDiff= $cms->getLike()->likesToDislikes($lp['id'])['like_diff'];
    $copyButtonId = 'copyButton_' . $lp['id'];
    echo '<section class="section-view-wrapper lp-view-wrapper">';
    echo '<img class="section-img" src="' . (isset($lp['thumbnail']) ? htmlspecialchars($lp['thumbnail']) : 'img/nature.jpg') . '">';
    echo '<div class="section-content-div">';
    echo '<h2><a class="views-link copy-link" href="viewLp.php?lpid=' . htmlspecialchars($lp['id']) . '&userId=' . htmlspecialchars($user_id) . '">' . htmlspecialchars($lp['title']) . '</a></h2>';
    echo '<p class="view-section-description">Description: ' . (isset($lp['description']) && !empty($lp['description']) ? htmlspecialchars($lp['description']) : 'No description given') . '</p>';
    echo '<div class="sec-div1">';
    echo '<p class="">Date Created: ' . htmlspecialchars(date('Y-m-d', strtotime($lp['date_created']))) . '</p>';
    echo ($lp['date_edited'] !== null) ? '<p class="">Date Edited:<br> ' . htmlspecialchars(date('Y-m-d H:i:s', strtotime($lp['date_edited']))) . '</p>' : '';
    echo '</div>';
    echo '<div class="sec-div1">';
    echo '<p class="">Language:' . htmlspecialchars($lp['language']) . '</p>';
    echo '<p class="">Category: ' . htmlspecialchars($lp['category']) . '</p>';
    echo '</div>';
    echo '<p class="likes">Likes:' . htmlspecialchars($likeDiff) . '</p>';
    echo '<p class="section-view-author place-grid-end">Author: ' . htmlspecialchars($lp['username']) . '</p>';
    echo '<a class="comments-button" href="comments.php?lpId=' . htmlspecialchars($lp['id']) . '">Comments</a>';
    echo $author ? '<a class="edit-button" href="editLp.php?lpId=' . htmlspecialchars($lp['id']) . '&userId=' . htmlspecialchars($user_id) . '">Edit this lp</a>' : '';
    echo '<button class="copy-url btn1" onclick="copyUrl(\'' . htmlspecialchars($lp['id']) . '\', \'' . htmlspecialchars($user_id) . '\')" id="' . htmlspecialchars($copyButtonId) . '"> Copy URL </button> ';
    echo '</div>';
    echo '</section>';
}
        ?>
    </section>
    <?php
    // adding the footer
    include 'components/footer.php' ?>
</body>

</html>