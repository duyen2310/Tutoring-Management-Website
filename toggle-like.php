<?php
include 'src/bootstrap.php';
$id = $cms->getSession()->id;

if ($id == 0) {
    header('Location: index.php');
}
// here we use the logic in which if we like and we had disliked previously it gets reversed  and if we had already liked
// clicking like again will cause us to unlike and th eopposite. we are also sending the response back to the javascript file!
if (isset($_POST['lpId'])) {
    $lpId = $_POST['lpId'];

    $cms->getDislike()->delete([$lpId, $id]);

    $liked = $cms->getLike()->get([$lpId, $id]);

    if ($liked) {
        $cms->getLike()->delete([$lpId, $id]);
    } else {
        $cms->getLike()->create([$lpId, $id]);
    }

    echo json_encode(['liked' => !$liked]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}