<?php
// This page uses the same ideas used in editSection so i will not be repeating most of the comments
include 'components/header.php';
$outputMessage = "";
$error = "";
$id = $cms->getSession()->id;
if (isset($_GET['lpId']) && isset($_GET['userId'])) {
    $author = false;
    $outputMessage = "";
    $error = "";
    $lpId = $_GET['lpId'];
    $userId = $_GET['userId'];
    $user = $cms->getUser()->getOne($_GET['userId']);
    $lp = $cms->getLp()->get($lpId);
    
    if ($userId != $id) {
        header('Location: index.php');
        exit();
    }
    if ($cms->getLp()->get($lpId) == false) {
        header('Location: page404.php');
        exit();
    }
    if (isset($_POST['edit-lp'])) {
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            if (empty($title)) {
                $outputMessage = "Title not changed<br>";
            } else if (strlen($title) >= 1 && strlen($title) <= 50) {
                if ($cms->getLp()->updateTitle($lpId, $title)) {
                    $outputMessage .= 'Title changed successfully!<br>';
                } else {
                    $error .= 'Error occured';
                }
            } else {
                $error .= "Title must be between 1 and 50 characters";
            }
        }
        if (isset($_FILES['section-image']) && $_FILES['section-image']['size'] > 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($_FILES['section-image']['type'], $allowedTypes)) {
                if ($_FILES['section-image']['size'] < (1 * 1024 * 1024)) {
                    $fileExtension = pathinfo($_FILES['section-image']['name'], PATHINFO_EXTENSION);
                    $destinationPath = "uploads/lp/$lpId.$fileExtension";
                    if ($cms->getLp()->editThumbnail($lpId, $destinationPath)) {
                        try {
                            $allFiles = glob("uploads/lp/$lpId.*");
                            foreach ($allFiles as $file) {
                                if ($file != $destinationPath) {
                                    unlink($file);
                                }
                            }
                            move_uploaded_file($_FILES['section-image']['tmp_name'], $destinationPath);
                        } catch (Exception $e) {
                            $error .= "Error occured";
                        }
                        $outputMessage .= "Image uploaded successfully.<br>";
                    } else {
                        $error .= "Error occured";
                    }
                } else {
                    $error .= 'File is too big<br>';
                }
            } else {
                $error .= 'Format not supported <br>';
            }
        } else {
            $outputMessage .= "No changes were made to the image<br>";
        }
        // here we are doing a check for the category and later language and editing them
        if (isset($_POST['categories'])) {
            $category = $_POST['categories'];
            if ($category == 0) {
                $outputMessage .= "No changes were made to the category<br>";
            } else {
                if ($cms->getLp()->updateCategory($lpId, $category)) {
                    $outputMessage .= "Category changed";
                } else {
                    $error .= "Failed to change category";
                }
            }
            if (isset($_POST['language'])) {
                $language = $_POST['language'];
                if ($language == 0) {
                    $outputMessage .= "No changes were made to the category<br>";
                } else {
                    if ($cms->getLp()->updateLanguage($lpId, $language)) {
                        $outputMessage .= "Language changed";
                    } else {
                        $error .= "Failed to change language";
                    }
                }
            }
        }
    }
    // check for the description
    if (isset($_POST['description'])) {
        $bio = trim($_POST['description']);
        if (empty($bio)) {
            $outputMessage .= "Bio not changed";
        } else if (strlen($bio)>0 && strlen($bio) <= 500) {
            if ($cms->getLp()->updateDescription($lpId, $bio)) {
                $outputMessage .= "Uploaded description<br>";
            } else {
                $error .= "Uploading failed";
            }
        } else {
            $error .= 'Description is too long (more than 500 characters)';
        }
}
// trying to delete it and redirecting the user to their profile if done so correctly
    if (isset($_POST['delete-form'])) {

        if ($cms->getLp()->deleteLp($lpId)) {

            if (file_exists($lp['thumbnail'])) {
                unlink($lp['thumbnail']);
            }
            header("Location: viewLps.php?user_id=" . $userId);
        } else {
            $error .= "Failed to delete";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit lp <?php echo $lp['title'] ?></title>
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/lp.js"></script>
    <script></script>
</head>

<body>
    <section class="hidden delete-section">
        <div id="backdrop">
            <div class="delete-popup">
                <h1>Are you sure you want to delete Lp <?php echo htmlspecialchars($lp['title'])  ?></h1>
                <form action="" class="delete-form" method="post">
                    <input type="submit" name="delete-form" id="delete-yes" value="Yes" class="delete-btn">
                    <button class="delete-btn">No</button>
                </form>
            </div>
        </div>
    </section>

    <section class="base-section main-section add-lp-form add-section">
        <a class="nav-anchor" id="back-to-lp" href="<?php echo "viewLps.php?user_id=" . $userId; ?>">Go back to the profile learning path's</a>
        <div class="main-div">
            <h1>Edit Lp <?php echo htmlspecialchars($lp['title']);  ?></h1>
            <form action="" class="image-upload-form" method="post" enctype="multipart/form-data">
                <img id="profile-pic" src="<?php echo isset($lp['thumbnail']) ? $lp['thumbnail'] : 'img/questionmark.png'; ?>" alt="">
                <label for="profile-image" id="image-label">Change LP thumbnail</label>
                <input type="file" name="section-image" id="profile-image" accept="image/jpeg,image/png,image/jpg">
                <div>
                    <label for="title">Change the title of the Lp</label>
                    <input type="text" name="title" id="">
                </div>
                <div>
                    <label for="title">Change Description</label>
                    <textarea name="description" id="description description1"></textarea>
                </div>
                <div>
                    <label for="language">Language</label>
                    <select name="language" id="language">
                        <option value="0">Make no changes</option>
                        <option value="1">English</option>
                        <option value="2">Mandarin Chinese</option>
                        <option value="3">Spanish</option>
                        <option value="4">Portuguese</option>
                        <option value="5">Albanian</option>
                        <option value="6">Hindi</option>
                        <option value="7">Vietnamese</option>
                    </select>
                </div>
                <div>
                    <label for="categories">Categories</label>
                    <select name="categories" id="categories">
                        <option value="0">Make no changes</option>
                        <option value="1">Technology and IT</option>
                        <option value="2">Design and Creativity</option>
                        <option value="3">Business and Entrepreneurship</option>
                        <option value="4">Health and Wellness</option>
                        <option value="5">Science and Math</option>
                        <option value="6">Humanities and Social Sciences</option>
                    </select>
                </div>
                <input type="submit" class="btn1" name="edit-lp" value="Submit Lp">
                <button class="btn1" id="delete-acc-btn">Click here to delete Lp</button>
                <p class="valid-text text-align"><?php echo $outputMessage ?></p>
                <p class="error"><?php echo $error ?></p>
            </form>
        </div>

    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>