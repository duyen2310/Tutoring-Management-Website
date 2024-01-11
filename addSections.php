<?php
include 'components/header.php';
$id = $cms->getSession()->id;

// adding a section is very similar to editing them the only difference is that here
// title and url are required no matter what
if (isset($_GET['lpid']) && isset($_GET['userId'])) {
    $outputMessage="";
    $error="";
    $userId = $_GET['userId'];
    $lpid = $_GET['lpid'];
    if($userId != $id){
        header('Location: index.php');
    }
    $user = $cms->getUser()->getOne($id);
    $outputMessage = "";
    $error = "";
    if($cms->getLp()->get($lpid) == false){
        header('Location: page404.php');
        exit(); 
    }
    if (isset($_POST['upload-section'])) {
        // checking title and url and if theyre both successful we create a section and return the id of the new section
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            if (strlen($title) >= 1 && strlen($title) <= 50) {
                if (isset($_POST['url'])) {
                    $url = $_POST['url'];
                    if (strlen($url) >= 1 && strlen($url) <= 200 && Validate::validateUrl($url)) {
                        try {
                            $secId = $cms->getSection()->createSection($title, $lpid, $url);
                            //redirecting the user to the lp to show the new section has been added if successful
                            header("Location: viewLp.php?lpid=$lpid&userId=" . $userId);
                            // which we ethen use to upload the image in the same way we did in other files if the user has chosen to do so
                            if (isset($_FILES['section-image']) && $_FILES['section-image']['size'] > 0) {
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                                if (in_array($_FILES['section-image']['type'], $allowedTypes)) {
                                    if ($_FILES['section-image']['size'] < (1 * 1024 * 1024)) {
                                        $fileExtension = pathinfo($_FILES['section-image']['name'], PATHINFO_EXTENSION);
                                        $destinationPath = "uploads/section/$secId.$fileExtension";
                                        if($cms->getSection()->addThumbnail($secId,$destinationPath)){
                                            try {
                                                $allFiles = glob("uploads/section/$secId.*");
                                                foreach ($allFiles as $file) {
                                                    if ($file != $destinationPath) {
                                                        unlink($file);
                                                    }
                                                }
                                                move_uploaded_file($_FILES['section-image']['tmp_name'], $destinationPath);
                                            } catch (Exception $e) {
                                                $error .= "Error occured";
                                            }
                                            $outputMessage.="Image uploaded successfully.<br>";
                                        }
                                        else{
                                            $error .= "Error occured";
                                        }
                                    } else {
                                        $error .= 'File is too big<br>';
                                    }
                                } else {
                                    $error .= 'Format not supported <br>';
                                }
                                
                            }
                            // if theyve chosen to post a description we do the following check as with every other file
                            if (isset($_POST['description'])) {
                                $bio = trim($_POST['description']);
                                if (strlen($bio) <= 500) {
                                    if ($cms->getSection()->addDescription($secId, $bio)) {
                                        $outputMessage .= "Uploaded description<br>";
                                    } else {
                                        $error .= "Uploading failed";
                                    }
                                } else {
                                    $error .= 'description is too long (more than 500 characters)';
                                }
                            }
                        } catch (Exception $e) {
                            echo $e;
                        }
                        // working through and displaying all the possible errors
                    }
                    else{
                        $error .= "Url must be between 1 and 200 characters";
                    }
                }
                else{
                    $error .= "Please add a valid url";
                }
            }
            else{
                $error .= "Please enter a title between 1 and 50 characters";
            }
        }
        else{
            $error  .= "Please enter a title";
        }
    }
} else {
    header('Location: index.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Section</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/section.js"></script>
</head>

<body>
    <section class="base-section main-section add-lp-form add-section">
    <a class="nav-anchor" id="back-to-lp" href="<?php echo "viewLp.php?lpid=$lpid&userId=" . $userId; ?>">Go back to the learning path</a>
        <div class="main-div">
            <h1>Add a section for the learning path!</h1>
            <form action="" class="image-upload-form" method="post" enctype="multipart/form-data">
                <img id="profile-pic" src="img/nature.jpg" alt="">
                <label for="profile-image" id="image-label">Upload section thumbnail</label>
                <input type="file" name="section-image" id="profile-image" accept="image/jpeg,image/png,image/jpg">
                <div>
                    <label for="title">Input the title of the section</label>
                    <input type="text" name="title" id="">
                </div>
                <div>
                    <label for="url">Input the url for the video:</label>
                    <input type="url" name="url" id="url">
                </div>
                <div>
                    <label for="title">Description</label>
                    <textarea name="description" id="description description1"></textarea>
                </div>
                <input type="submit" class="btn1" name="upload-section" value="Submit section">
                <p class="valid-text text-align"><?php echo $outputMessage ?></p>
                    <p class="error"><?php echo $error ?></p>
            </form>
        </div>

    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>