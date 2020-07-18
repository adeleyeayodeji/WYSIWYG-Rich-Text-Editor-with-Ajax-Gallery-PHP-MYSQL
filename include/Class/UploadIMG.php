<?php
    class UploadIMG extends Dbh
    {
        protected static $imagename;
        protected static $src;
        protected static $description;
        protected static $delete_src;

        public function uploadIMGdata($name, $src, $description)
        {
            self::$imagename = $name;
            self::$src = $src;
            self::$description = $description;

            //Check if the base is more 5
            $sql = "SELECT * FROM gallery";
            $query = $this->connect()->query($sql);
            $total = $query->rowCount();
            if ($total >= 10) {
                return array("info" => "Limit Exceeded");
            } else {
                $sql = "INSERT INTO gallery(imagesrc, description, name) VALUES(? , ?, ?)";
                $query = $this->connect()->prepare($sql);
                $query->execute([self::$src, self::$description, self::$imagename]);
                if ($query) {
                    return array("info" => "Successfully uploaded", "src" => self::$src, "name" => self::$imagename);
                }else{
                    return array("info" => "Error uploading image");
                }
            }

        }

        public function checkImage($name)
        {
            self::$imagename = $name;

            $sql = "SELECT * FROM gallery WHERE name = ?";
            $query = $this->connect()->prepare($sql);
            $query->execute([self::$imagename]);
            // return result
            $count = $query->rowCount();
            if($count < 1){
                return "empty";
            }else{
                return "valid";
            }
        }

        public function deleteIMG($name)
        {
            self::$imagename = $name;

            //Check if in base
            $sql = "SELECT * FROM gallery WHERE name = ?";
            $query = $this->connect()->prepare($sql);
            $query->execute([self::$imagename]);
            $result = $query->fetch();
            self::$delete_src = $result->imagesrc;
            // return result
            $count = $query->rowCount();
            if($count == 0){
                return array("info" => "Image already deleted", "Deleted_src" => self::$imagename);
            }else{
                $sql = "DELETE FROM gallery WHERE name = ?";
                $query = $this->connect()->prepare($sql);
                $query->execute([self::$imagename]);
                if ($query) {
                    unlink("../image/".self::$delete_src);
                    return array("info" => "Image deleted", "Deleted_src" => self::$imagename);
                }else{
                    return array("info" => "Error deleting image");
                }
            }
        }
    }
    
?>