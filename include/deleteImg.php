<?php
    include "./autoload.php";
    $base = new UploadIMG;
    header("Content-Type: application/json");
    
    if (isset($_POST["delete"])) {
        $name = $_POST["name"];
        $delete = $base->deleteIMG($name);
        echo json_encode($delete);
    }
?>