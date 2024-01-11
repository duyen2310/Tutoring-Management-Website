<?php
// header which includes bootstrap
include 'src/bootstrap.php';
// storing the id in a variable named loggin
$loggedIn =  $cms->getSession()->id;
// if they log out we delete the session and direct them to the index
if (isset($_POST['logout-form'])) {
    $cms->getSession()->delete();
    header('Location: index.php');
    exit();
}

?>

<header>
    <div>
        <h1><a href="">PathFusion</a></h1>
    </div>
    <nav>
        <a href="login.php" class="<?php echo $loggedIn == 0 ? 'shown' : 'hidden'; ?> nav-anchor">Login</a>
        <a href="register.php" class="<?php echo $loggedIn == 0 ? 'shown' : 'hidden'; ?> nav-anchor">Register</a>
        <a href="index.php" class="nav-anchor">Home</a>
        <a href="profile.php" class="<?php echo $loggedIn == 0 ? 'hidden' : 'shown'; ?> nav-anchor">Profile</a>
        <a href="addLp.php?userId=<?php echo $loggedIn; ?>" class="<?php echo $loggedIn == 0 ? 'hidden' : 'shown'; ?> nav-anchor">Create learning path</a>
        <button class="<?php echo $loggedIn == 0 ? 'hidden' : 'shown'; ?>  btn1" id="nav-btn1">Log Out</button>
        </form>
    </nav>
</header>
<!--hidden popout that only shows when user clicks logout-->
<section class="hidden">
    <div id="backdrop">
        <div class="logout-popup">
            <h1>Are you sure you want to log out?</h1>
            <form action="" class="logout-form" method="post">

                <input type="submit" name="logout-form" id="" value="Yes" class="lp-btn">
                <button class="lp-btn">No</button>
            </form>
        </div>
    </div>
</section>
<script src="js/header.js"></script>