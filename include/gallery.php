<?php
    include "./autoload.php";
    header("Content-Type: application/json");

    $response = new PostView;
    $results = $response->Gallery();
    echo json_encode($results);
?>