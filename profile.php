<?php
include 'components/header.php';
$username = $cms->getSession()->username;
$id = $cms->getSession()->id;
$authorized = false;

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $user = $cms->getUser()->getOne($user_id);

    if ($user) {
        if ($user_id == $id) {
            $authorized = true;
        } else {
            $authorized = false;
        }
    } else {
        header('Location: page404.php');
        exit();
    }
} else {
    $user = $cms->getUser()->getOne($id);
    $authorized = true;
}

$time = strtotime($user['date_joined']);
$time_joined = date('Y-m-d', $time);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/main.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($user['username']) ?>'s profile</title>
</head>

<body>
    <section class="main-profile-section">
        <div class="main-profile-div main-profile-show">
            <h2>Name: <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
            <img id="profile-pic" src="<?php echo (is_null($user['image_url']) ? 'img/default.png' : $user['image_url']); ?>" alt="">
            <h2>Username: <?php echo htmlspecialchars($user['username']); ?></h2>
            <h2>Email: <?php echo $user['email'] ?></h2>
            <h2>Date joined : <?php echo $time_joined ?></h2>
            <h2>Description: </h2>
            <p><?php echo $user['description'] !== null ? htmlspecialchars($user['description']) : "This user has no description"; ?></p>
            <?php echo $authorized ? '<a href="profileEdit.php?user_id=' . $user['id'] . '" class="nav-anchor">Click here to edit profile</a>' : ''; ?>
            <?php echo '<a class="learning-paths-link" href="viewLps.php?user_id=' . $user['id'].'">' . ($authorized ? 'My' : 'User\'s') . ' learning paths</a>'; ?>
        </div>
    </section>
    <section class="dashboard">
        <div class="dashboard-div">
        </div>
    </section>
    <?php include 'components/footer.php' ?>
</body>

</html>