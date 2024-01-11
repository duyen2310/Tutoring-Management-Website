<?php
include 'components/header.php';
$outputMessage = "";
$error = "";
$id = $cms->getSession()->id;
if (isset($_GET['secId']) && isset($_GET['userId'])) {
    $author = false;
    $outputMessage = "";
    $error = "";
    $secId = $_GET['secId'];
    $userId = $_GET['userId'];
    $user = $cms->getUser()->getOne($_GET['userId']);
    $section = $cms->getSection()->get($secId);
    if($section == false){
        header('Location: page404.php');
        exit(); 
    }
    $sectionId = $section['id'];
    $lpId=$section['lp_id'];


    // if we cant find lp then redirect to page not found
    if($cms->getLp()->get($lpId) == false){
        header('Location: page404.php');
        exit(); 
    }
    if (isset($_POST['edit-section'])) {
        header("Location: viewLp.php?lpid=$lpId&userId=" . $userId);
        // making sure title is set and that if its empty we just show that title isnt changed
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            if (empty($title)) {
                $outputMessage = "Title not changed<br>";
                // if its between these values user is trying to change it and is within limits to do so 
            } else if (strlen($title) >= 1 && strlen($title) <= 50) {
                // so we call the updateTitle method and if it works we display that it did
                if ($cms->getSection()->updateTitle($sectionId, $title)) {
                    $outputMessage .= 'Title changed successfully!<br>';
                } else {
                    $error .= 'Error occured';
                }
            } else {
                $error .= "Title must be between 1 and 50 characters";
            }
        }
        // dealing with the url seperatly but more or less using the same logic
        if (isset($_POST['url'])) {
            $url = $_POST['url'];
            if (empty($url)) {
                $outputMessage .= "Url not changed<br>";
            } else if (strlen($url) >= 1 && strlen($url) <= 200 && Validate::validateUrl($url)) {
                if ($cms->getSection()->updateUrl($sectionId, $url)) {
                    $outputMessage .= "Url changed successfully!<br>";
                } else {
                    $error .= 'Error occured';
                }
            } else {
                $error = "Url must be between 1 and 200 characters";
            }
        }
        // dealing with the file upload which is the exact same proccess as the one used in profileEdit just using different variable names
        // and calling different methods
        if (isset($_FILES['section-image']) && $_FILES['section-image']['size'] > 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($_FILES['section-image']['type'], $allowedTypes)) {
                if ($_FILES['section-image']['size'] < (1 * 1024 * 1024)) {
                    $fileExtension = pathinfo($_FILES['section-image']['name'], PATHINFO_EXTENSION);
                    $destinationPath = "uploads/section/$sectionId.$fileExtension";
                    if ($cms->getSection()->editThumbnail($sectionId, $destinationPath)) {
                        try {
                            $allFiles = glob("uploads/section/$sectionId.*");
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
        }
        else{
            $outputMessage .= "No changes were made to the image<br>";
        }
        // same logic for the description aswell
        if (isset($_POST['description'])) {
            $bio = trim($_POST['description']);
            if (empty($bio)) {
                $outputMessage .= "Bio not changed";
            } else if (strlen($bio)>0 && strlen($bio) <= 500) {
                if ($cms->getSection()->editDescription($secId, $bio)) {
                    $outputMessage .= "Uploaded description<br>";
                } else {
                    $error .= "Uploading failed";
                }
            } else {
                $error .= 'Description is too long (more than 500 characters)';
            }
    }}
} 
// trying to delete the form and then redirecting back to the lp which it belongs to 
if(isset($_POST['delete-form'])){

    if($cms->getSection()->deleteSection($section['id'])){
        header("Location: viewLp.php?lpid=$lpId&userId=" . $section['user_id']);
        if (file_exists($section['thumbnail'])) {
            unlink($section['thumbnail']);
        }
}
    else{
        $error .= "Failed to delete";
    }

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit section <?php echo $section['title'] ?></title>
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/lp.js"></script>

</head>

<body>
<section class="hidden delete-section">
    <div id="backdrop">
        <div class="delete-popup" >
            <h1>Are you sure you want to delete section <?php  echo htmlspecialchars($section['title'])  ?></h1>
            <form action="" class="delete-form" method="post">
                <input type="submit" name="delete-form" id="delete-yes" value="Yes" class="delete-btn">
                <button class="delete-btn">No</button>
            </form>
        </div>
    </div>
</section>

    <section class="base-section main-section add-lp-form add-section">
    <a class="nav-anchor" id="back-to-lp" href="<?php echo "viewLp.php?lpid=$lpId&userId=" . $userId;?>">Go back to the learning path</a>
        <div class="main-div">
            <h1>Edit Section <?php echo htmlspecialchars($section['title'])  ?></h1>
            <form action="" class="image-upload-form" method="post" enctype="multipart/form-data">
            <!--Using same idea as used in profile to show a "placeholder"-->
            <img id="profile-pic" src="<?php echo isset($section['thumbnail']) ? $section['thumbnail'] : 'img/nature.jpg'; ?>" alt="">
                <label for="profile-image" id="image-label">Change section thumbnail</label>
                <input type="file" name="section-image" id="profile-image" accept="image/jpeg,image/png,image/jpg">
                <div>
                    <label for="title">Change the title of the section</label>
                    <input type="text" name="title" id="">
                </div>
                <div>
                    <label for="url">Change the url for the video:</label>
                    <input type="url" name="url" id="url">
                </div>
                <div>
                    <label for="title">Change Description</label>
                    <textarea name="description" id="description description1"></textarea>
                </div>
                <input type="submit" class="btn1" name="edit-section" value="Submit section">
                <button class="btn1" id="delete-acc-btn">Click here to delete section </button>
                <p class="valid-text text-align"><?php echo $outputMessage ?></p>
                <p class="error"><?php echo $error ?></p>
            </form>
        </div>

    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>