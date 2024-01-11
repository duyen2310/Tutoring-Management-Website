<?php
include 'components/header.php';
$username = $cms->getSession()->username;
$id = $cms->getSession()->id;

if ($id == 0) {
    header('Location: index.php');
}
// making sure that the user that is trying to edit the profile is the same one as the one specified in the url else they get redirected to
// their profile
if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];
    if($user_id != $id){
        header('Location: profileEdit.php?'. $user_id);
    }
}
// finding user from the session and declaring the outputMessage if its correct and the error if not
$user = $cms->getUser()->getOne($id);
$outputMessage = "";
$error = "";
if (isset($_POST['update-form'])) {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $description = $_POST['description'];
    // here we handle the uploading of the file if it is uplaoded
    if (isset($_FILES['profile-image']) && $_FILES['profile-image']['size'] > 0) {
        // specifying which files are allowed
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        // if the file is in the allowed types
        if (in_array($_FILES['profile-image']['type'], $allowedTypes)) {
            // making sure its not over 1mb
            if ($_FILES['profile-image']['size'] < (1 * 1024 * 1024)) {
                // getting the file extension and creating a destinationPath with it which is used to
                $fileExtension = pathinfo($_FILES['profile-image']['name'], PATHINFO_EXTENSION);
                $destinationPath = "uploads/user/$id.$fileExtension";
                // add it to the image_url in th edatabase
                if ($cms->getUser()->addImage($id, $destinationPath)) {
                    try {
                        // also trying to delete all the other files that end with different types but have the same name as to
                        // not create lets say 1.jpg and 1.png (which would cause storage to be used for no reason)
                        $allFiles = glob("uploads/$id.*");
                        foreach ($allFiles as $file) {
                            // if the file isnt the one we just uploaded delete it
                            if ($file != $destinationPath) {
                                unlink($file);
                            }
                        }
                    } catch (Exception $e) {
                        return false;
                    }
                    // outputing the fact it worked correctly and moving the file to its destination
                    $outputMessage = "Uploaded image (might have to refresh to see result)<br>";
                    move_uploaded_file($_FILES['profile-image']['tmp_name'], $destinationPath);
                    //handling all the errors with else statements
                } else {
                    $error = "Uploading failed";
                }
            } else {
                $error .= 'File is too big<br>';
            }
        } else {
            $error .= 'Format not supported <br>';
        }
    }
    // adding/editing the bio making sure its not more than 500 long (if it's - then nothign will be shown)
    if (isset($_POST['description'])) {
        $bio = trim($_POST['description']);
        if (strlen($bio) <= 500) {
            // adding description if check is passed and showing that it worked correctly
            if ($cms->getUser()->addDescription($id, $bio)) {
                $outputMessage .= "Uploaded description<br>";
            } else {
                // if it didnt show upload failed
                $error .= "Uploading failed";
            }
        } else {
            // show bio is too long
            $error .= 'Bio is too long (more than 500 characters)';
        }
    }
    // if username is more than 0 (which means the user is trying to change it) validate it and use the same execution
    // as we did for creating it but instead we are just calling the editUsername function
    if (isset($newUsername) && trim($newUsername) > 0) {
        if (!Validate::isUsernameValid($_POST['username'])) {
            $error .= "Please enter a valid username!";
        } else {
            if ($cms->getUser()->editUsername($id, $newUsername)) {
                $cms->getSession()->username = $newUsername;
                $outputMessage .= 'Uploaded username<br>';
            }
            else{
                $error .= "Username already exists";
            }
        }
    }
    // same as the username but with the email
    if (isset($newEmail) && trim($newEmail) > 0) {
        if (!Validate::isEmail($_POST['email'])) {
            $error .= "Please enter a valid username!";
        } else {
            if ($cms->getUser()->editEmail($id, $newEmail)) {
                $outputMessage .= 'Uploaded email';
            }
            else{
                $error .= "Email already exists";
            }
        }
    }
}
// here we try to delete the accoutn and if we do delete it successfully(the session with it aswell) we locate the user back to index
// and remove the image that was associated with that account
if(isset($_POST['delete-form'])){
    try{
    $cms->getUser()->deleteAccount($id);
    $cms->getSession()->delete();
    header('Location: index.php');
    if (file_exists($user['image_url'])) {
        unlink($user['image_url']);
    }
    }
    catch(Exception $e){
        $error = "Something went wrong, account not deleted.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username) ?>'s profile</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/profile.js"></script>
</head>

<body>
<section class="hidden delete-section">
    <div id="backdrop">
        <div class="delete-popup" >
            <h1>Are you sure you want to delete your account?</h1>
            <form action="" class="delete-form" method="post">
                <input type="submit" name="delete-form" id="delete-yes" value="Yes" class="delete-btn">
                <button class="delete-btn">No</button>
            </form>
        </div>
    </div>
</section>

    <section class="main-profile-section">
        <div class="main-profile-div">
            <div class="image-upload-div">
                <h2 class="text-align">Change bio and/or profile picture</h2>
                <form action="" class="image-upload-form" method="post" enctype="multipart/form-data">
                    <!--If the imageurl is null then we use a default one from the folder-->
                    <img id="profile-pic" src="<?php echo (is_null($user['image_url']) ? 'img/default.png' : $user['image_url']); ?>" alt="">
                    <label for="profile-image" id="image-label">Update Image</label>
                    <input type="file" name="profile-image" id="profile-image" accept="image/jpeg,image/png,image/jpg">
                    <div>
                        <label for="username">Update your username: </label>
                        <input type="text" id="username" name="username">
                    </div>
                    <div>
                        <label for="email">Update your email: </label>
                        <input type="email" id="email" name="email">
                    </div>
                    <label for="description" id="description">Update description:</label>
                    <textarea name="description" id="description" cols="60" rows="4"></textarea>
                    <input type="submit" name="update-form" class="btn1 submit">
                    <button class="btn1" id="delete-acc-btn">Click here to delete account</button>
                    <p class="valid-text text-align"><?php echo $outputMessage ?></p>
                    <p class="error"><?php echo $error ?></p>
                </form>
            </div>
        </div>
    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>