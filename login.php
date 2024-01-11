<?php
include 'src/bootstrap.php';

// validating that the email is of proper format
if (isset($_POST['login-form'])) {
    if (!Validate::isEmail($_POST['email']) || !Validate::isValidName($_POST['email'])) {
        $email_error = 'Please enter a valid email address!';
    }
    // trying to log user in
    $user = $cms->getUser()->login($_POST['email'], $_POST['password']);

    // if it doesnt return false (password and email match) then show that the user is logged in
    // create a session and head the user to index
    if ($user !== false) {
        $creationSuccess = 'Logged in successfully.';
                $session = $cms->getSession()->create([
                    'id'=>$user['id'],
                    'username'=>$user['username']
                ]);
                header('Location: index.php');
    } else {
        // if not show the error below
        $creationError = 'Email does not match password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
</head>

<body>
    <div class="form-wrap-div">
        <form action="" method="post" id="login-form">
            <div id="form-wrap-in">
            <div>
            <label for="email">Email: </label>
            <input type="email" name="email" placeholder="Your email">
            <p class="error emailerror"><?php echo isset($email_error) ? $email_error : ''; ?></p>
            </div>
            <div>
            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Your password">
            </div>
            <input type="submit" class="main-btn" id="form-btn" name="login-form">
            <p class="valid-text <?php echo empty($creationSuccess) ? 'hidden' : ''; ?>"><?php echo isset($creationSuccess) ? $creationSuccess : ''; ?></p>
            <p class="error <?php echo empty($creationError) ? 'hidden' : ''; ?>" id="creationError"><?php echo isset($creationError) ? $creationError : ''; ?></p>
            </div>
            <p>Don't have an account? <a href="register.php" class="nav-anchor">register here</a></p>
        </form>
        
        <div class="register-right-wrap">
        <div><?php include 'img/login.svg' ?></div>
            <h1 class="login-h1">Login here or <a href="index.php">go to the main page</a></h1>
        </div>
    </div>
    
</body>

</html>