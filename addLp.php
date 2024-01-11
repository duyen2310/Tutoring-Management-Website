<?php
// here we use more or less the same uploading logic as with addSections.php with a few minor differences
include 'components/header.php';
$id = $cms->getSession()->id;

if ($id == 0) {
    header('Location: index.php');
}
if (isset($_GET['userId'])){
    if($_GET['userId'] != $id){
        header('Location: index.php');
    }
}
$user = $cms->getUser()->getOne($id);

$outputMessage = "";
$error = "";
// we are making sure all three title language and categories are set before continuing with the rest the same way we did
//
if (isset($_POST['upload-lp'])) {
    if (isset($_POST['title'])) {
        $title = $_POST['title'];
        if(strlen($title) < 1){
            $error .= "Please add a title";
        }

        if (strlen($title) >= 1 && strlen($title) <= 50) {
            if (isset($_POST['language']) && !empty($_POST['language'])) {
                $selectedLanguage = $_POST['language'];
                if (isset($_POST['categories']) && !empty($_POST['categories'])) {
                    $selectedCategory = $_POST['categories'];
                    try {
                        $lpId = $cms->getLp()->createLp($title, $id, $selectedLanguage, $selectedCategory);
                        // locating them to add a new section for the newly made lp
                        header("Location: addSections.php?lpid=$lpId&userId=". $user['id']);
                        $outputMessage = "Lp created<br>";
                        if (isset($_FILES['lp-image']) && $_FILES['lp-image']['size'] > 0) {
                            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                            if (in_array($_FILES['lp-image']['type'], $allowedTypes)) {
                                if ($_FILES['lp-image']['size'] < (1 * 1024 * 1024)) {
                                    $fileExtension = pathinfo($_FILES['lp-image']['name'], PATHINFO_EXTENSION);
                                    $destinationPath = "uploads/lp/$lpId.$fileExtension";
                                    if ($cms->getLp()->addThumbnail($lpId, $destinationPath)) {
                                        try {
                                            $allFiles = glob("uploads/$id.*");
                                            foreach ($allFiles as $file) {
                                                if ($file != $destinationPath) {
                                                    unlink($file);
                                                }
                                            }
                                            move_uploaded_file($_FILES['lp-image']['tmp_name'], $destinationPath);
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
                        if (isset($_POST['description'])) {
                            $bio = trim($_POST['description']);
                            if (strlen($bio) <= 500) {
                                if ($cms->getLp()->addDescription($lpId, $bio)) {
                                    $outputMessage .= "Uploaded description<br>";
                                } else {
                                    $error .= "Uploading failed";
                                }
                            } else {
                                $error .= 'Bio is too long (more than 500 characters)';
                            }
                        }
                    } catch (PDOException $e) {
                        if ($e->getCode() == '23000' && $e->errorInfo[1] == 1062) {
                            $error = "Learning Path already exists.";
                        } else {
                            $error = "Error... submission not completed.";
                        }
                    }
                } else {
                    $error = "Please select a category";
                }
            } else {
                $error .= "Please select a language";}
            }
        } else {
            $error .= "Title must be between 1 and 50 characters<br>";
        }
    }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/addlp.js"></script>
    <title>Create learning path</title>
</head>

<body>
    <section class="base-section add-lp-form lp-main-section">
        <div class="main-div" id="lp-form">
            <h1>Add a learning path!</h1>
            <form action="" class="image-upload-form" method="post" enctype="multipart/form-data">
                <img id="profile-pic" src="img/questionmark.png" alt="">
                <label for="profile-image" id="image-label">Upload lp thumbnail</label>
                <input type="file" name="lp-image" id="profile-image" accept="image/jpeg,image/png,image/jpg">
                <div>
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title">
                </div>
                <div>
                    <label for="title">Description</label>
                    <textarea name="description" id="description"></textarea>
                </div>
                <div>
                    <label for="language">Language</label>
                    <select name="language" id="language">
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
                        <option value="1">Technology and IT</option>
                        <option value="2">Design and Creativity</option>
                        <option value="3">Business and Entrepreneurship</option>
                        <option value="4">Health and Wellness</option>
                        <option value="5">Science and Math</option>
                        <option value="6">Humanities and Social Sciences</option>
                    </select>
                </div>
                <input type="submit" name="upload-lp" class="btn1">
                <p class="valid-text text-align"><?php echo $outputMessage ?></p>
                <p class="error"><?php echo $error ?></p>
            </form>
        </div>
    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>