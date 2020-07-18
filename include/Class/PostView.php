<?php
    class PostView extends PostModel
    {   

        public function Gallery()
        {
            //Get all images
            $sql = "SELECT * FROM gallery";
            $query = $this->connect()->query($sql);
            $results = $query->fetchAll();
            return $results;
        }

        
    }
    