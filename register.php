<?php
include 'src/bootstrap.php';
// this file is used to register users first we get the session
$session = $cms->getSession();

if (isset($_POST['register-form'])) {

    // errors to see how many errors we have, creationError and creationSuccess to display if it worked properly or not
    $errors = [];
    $creationError = '';
    $creationSuccess = '';
    // using the validate class to validate enaim firstname lastname(cant be numbeers) etc
    if (!Validate::isEmail($_POST['email']) || !Validate::isValidName($_POST['email'])) {
        $email_error = 'Please enter a valid email address!';
        array_push($errors, $email_error);
    }
    if (!VALIDATE::isValidName($_POST['first_name'])) {
        $first_name_error = 'Please enter a valid first name!';
        array_push($errors, $first_name_error);
    }
    if (!VALIDATE::isValidName($_POST['last_name'])) {
        $last_name_error = 'Please enter a valid last name!!';
        array_push($errors, $last_name_error);
    }
    if (!Validate::isPasswordSafe($_POST['password'])) {
        $password_error = "Please enter a valid password!";
        array_push($errors, $password_error);
    }
    if (!Validate::isUsernameValid($_POST['username'])) {
        $username_error = "Please enter a valid username!";
        array_push($errors, $username_error);
    }
    // if there are no errors found we procede to create the user
    if (empty($errors)) {
        try {
            $create = $cms->getUser()->create([
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'password' => $_POST['password'],
                'username' => $_POST['username'],
                'email' => $_POST['email']
            ]);
            // in the create function in user (check cms) we return an array of the errors if errors did occur, else we return true
            // if there are errors we check what caused the error and show the appropriate messaeg 
            if (!is_bool($create)) {
                if (in_array($_POST['username'], $create) && in_array($_POST['email'], $create)) {
                    $creationError = "Username and email already exist.";
                } else if (in_array($_POST['username'], $create)) {
                    $creationError = "Username already exists.";
                } else if (in_array($_POST['email'], $create)) {
                    $creationError = "Email already exists.";
                };
            }
            // if we dont get an array (means creation successful) back then we can find the user and create a session for that user!
            else{
                $id = $cms->getUser()->getUserId($_POST['email']);
                $session = $cms->getSession()->create([
                    'id'=>$id,
                    'username'=>$_POST['username']
                ]);
                // redirect user to index
                header('Location: index.php');
            }
            // if there was another error we just show that there was an error with the registering
        } catch (\Exception $e) {
            $creationError = "There was an error with the registering...";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="css/main.css" rel="stylesheet">
    </link>
    <script defer src="js/form.js"></script>
</head>

<body>
    <div class="form-wrap-div">
        <div class="register-left-wrap">
            <div><?php include 'img/register.svg' ?></div>
            <h1>Register here or <a href="index.php">go to the main page</a></h1>
        </div>
        <form action="" method="post">
            <div>
                <!--Only other part in which we use php for is to echo errors for each part and in total  if there was a creation error-->
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" placeholder="Your first name">
                <p class="error"><?php echo isset($first_name_error) ? $first_name_error : ''; ?></p>
            </div>
            <div>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" placeholder="Your last name">
                <p class="error"><?php echo isset($last_name_error) ? $last_name_error : ''; ?></p>
            </div>
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="Your username">
                <p class="error"><?php echo isset($username_error) ? $username_error : ''; ?></p>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="email@example.com">
                <p class="error"><?php echo isset($email_error) ? $email_error : ''; ?></p>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Your password">
                <p class="error"><?php echo isset($password_error) ? $password_error : ''; ?></p>
            </div>
            <input type="submit" class="main-btn" id="form-btn" name="register-form">
            <p class="valid-text <?php echo empty($creationSuccess) ? 'hidden' : ''; ?>"><?php echo isset($creationSuccess) ? $creationSuccess : ''; ?></p>
            <p class="error <?php echo empty($creationError) ? 'hidden' : ''; ?>" id="creationError"><?php echo isset($creationError) ? $creationError : ''; ?></p>
            <p>Already have an account? <a href="login.php" class="nav-anchor">login here</a></p>
        </form>
    </div>
</body>

</html>