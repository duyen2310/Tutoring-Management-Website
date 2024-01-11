<?php
// same idea but with dislike
include 'src/bootstrap.php';
$id = $cms->getSession()->id;

if ($id == 0) {
    header('Location: index.php');
}

if (isset($_POST['lpId'])) {
    $lpId = $_POST['lpId'];

    $cms->getLike()->delete([$lpId, $id]);

    $disliked = $cms->getDislike()->get([$lpId, $id]);

    if ($disliked) {
        $cms->getDislike()->delete([$lpId, $id]);
    } else {
        $cms->getDislike()->create([$lpId, $id]);
    }

    echo json_encode(['disliked' => !$disliked]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>