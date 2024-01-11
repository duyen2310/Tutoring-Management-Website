<?php
include 'components/header.php';
// Check if the form is submitted
$lps = $cms->getLp()->getAll();
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    // Filter the learning paths based on the search term
    $filteredLps = array_filter($lps, function ($lp) use ($searchTerm) {
        return stripos($lp['title'], $searchTerm) !== false || stripos($lp['description'], $searchTerm) !== false;
    });
} else {
    // If the form is not submitted, display all learning paths
    $filteredLps = $lps;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
</head>

<body>
    <div class="base-section hero">
        <div class="pathfusion-hero">
            <h2>PathFusion</h2>
            <h3>Learning Paths by you for you</h3>
        </div>
        <?php include 'img/astronaut.svg'; ?>
    </div>

    <section class="display-all main-section main-section-show">
    <form action="" method="get" id="select-form">
    <!-- Text search field -->
    <input type="text" class="search-text" name="search" placeholder="Search by title or description" value="<?php echo isset($searchTerm) ? htmlspecialchars($searchTerm) : ''; ?>">

    <!-- Category dropdown -->
    <select name="category">
        <option value="" selected>Select Category</option>
        <?php
        // fetching unique categories from $lps
        $categories = array_unique(array_column($lps, 'category'));
        
        // displaying options in the dropdown
        foreach ($categories as $category) {
            echo '<option value="' . htmlspecialchars($category) . '">' . htmlspecialchars($category) . '</option>';
        }
        ?>
    </select>

    <!-- Language dropdown -->
    <select name="language">
        <option value="" selected>Select Language</option>
        <?php
        // fetching unique languages from $lps
        $languages = array_unique(array_column($lps, 'language'));
        
        // display options in the dropdown
        foreach ($languages as $language) {
            echo '<option value="' . htmlspecialchars($language) . '">' . htmlspecialchars($language) . '</option>';
        }
        ?>
    </select>

    <!-- Search buttons -->
    <button class="btn1" id="search-btn" type="submit">Search</button>

    <!-- Reset button for when we want to go back to the beggining, this uses javascript on the onclick  -->
    <?php if (isset($_GET['search']) || isset($_GET['category']) || isset($_GET['language'])): ?>
        <button id="reset-btn" class="btn1" type="button" onclick="window.location.href='index.php'">Reset</button>
    <?php endif; ?>
</form>

        <?php
        foreach ($filteredLps as $lp) {
            // exact same logic used as in viewLps but edit's cannot be done from here!
            $likeDiff = $cms->getLike()->likesToDislikes($lp['id'])['like_diff'];
            $copyButtonId = 'copyButton_' . $lp['id'];
            echo '<section class="section-view-wrapper lp-view-wrapper">';
            echo '<img class="section-img" src="' . (isset($lp['thumbnail']) ? htmlspecialchars($lp['thumbnail']) : 'img/nature.jpg') . '">';
            echo '<div class="section-content-div">';
            echo '<h2><a class="views-link copy-link" href="viewLp.php?lpid=' . htmlspecialchars($lp['id']) . '&userId=' . htmlspecialchars($lp['user_id']) . '">' . htmlspecialchars($lp['title']) . '</a></h2>';
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
            echo '<button class="copy-url btn1" onclick="copyUrl(\'' . htmlspecialchars($lp['id']) . '\', \'' . htmlspecialchars($lp['user_id']) . '\')" id="' . htmlspecialchars($copyButtonId) . '"> Copy URL </button> ';
            echo '</div>';
            echo '</section>';
        }
        ?>

    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>