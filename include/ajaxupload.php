<?php
    include "./autoload.php";
    $base = new UploadIMG;
    header("Content-Type: application/json");
    // Compress image
    function compressImage($source, $destination, $quality) {

        $info = getimagesize($source);

        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);

        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);

        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);

        imagejpeg($image, $destination, $quality);

    }

if(is_array($_FILES)) {
     // Getting file name
     $filename = $_FILES['userImage']['name'];
    if ($filename == "" || $filename == null) {
        echo json_encode(array("info" => "null"));
    } else {
        //Rewriting file name
       $extension = explode(".",$filename);
   
       //Check if image already exit
       $checkname = $base->checkImage($extension[0]);
   
       if ($checkname == "valid") {
           $oldname = $extension[0]."_new_".rand(0, 10000);
   
           $newname = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', trim($oldname))));   
       }else if ($checkname == "empty") {
           $oldname = $extension[0];
   
           $newname = preg_replace('/[^A-Za-z0-9\-]/', '', strtolower(str_replace(' ', '-', trim($oldname))));
       }
       $URL = $newname.".".$extension[1];
   
        // Location
        $location = "../image/".$URL;
   
        $valid_ext = array('png','jpeg','jpg');
        // file extension
        $file_extension = pathinfo($location, PATHINFO_EXTENSION);
        $file_extension = strtolower($file_extension);
        // Check extension
        if(in_array($file_extension,$valid_ext)){  
            // Compress Image
            if(compressImage($_FILES['userImage']['tmp_name'],$location,100)){
                //   Do nothing;
            }else{
                //Send data to base
                $response = $base->uploadIMGdata($oldname, $URL, "");
                echo json_encode($response);
            };
   
            }else{
                echo json_encode(array("info" => "Invalid file type."));
            }
    }
}
?>