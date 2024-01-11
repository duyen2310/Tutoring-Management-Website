<?php
include 'src/bootstrap.php';

// if the post (which is sent by the javascript file for updating views is is set then we increment the views of the section which is clicked )
if (isset($_POST['section_id'])) {
    $section_id = $_POST['section_id'];


    echo $cms->getSection()->incrementViews($section_id);
}
?>
