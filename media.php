<?php
/**
 * Created by PhpStorm.
 * User: Robert Tunyi
 * Date: 8/30/14
 * Time: 3:58 PM
 */
abstract class  Media {
    private $mediaType; // these are extentions could be jpeg etc
    private $dbLink;
    private $createdBy;
    protected $mediaId;
    protected $unique_id; //unique identifiyer for this content

    function _construct($mediaType , $dbLink, $createdBy){
        $this->mediaType =$mediaType;
        $this->dbLink =$dbLink;
        $this->createdBy = $createdBy;
        $this->generate_Unique_Id();
    }
    protected function generate_Unique_Id(){
        $this->unique_id = uniqid();
    }
    public function get_Unique_Id(){
        return $this->unique_id;
    }
    abstract public function getFilePath();

}

Class Image extends Media{
    private $fileUrl; //url to image folder
    private $filename;
    private $thumbnUrl;
    private $fileSize;
    protected $db;
    protected $pdo;

    public static function returnImageObject($mediaType , DB_CONNECT $dbLink, $createdBy ,$base64_string ){
        $object = new Image();
        echo "in fuction";
        $object->_construct_Image_Object($mediaType ,$dbLink, $createdBy ,$base64_string);
        return $object;

    }

    function _construct( ){
        $this->fileUrl = "gs://ripl-images/";
        $this->thumbnUrl = "gs://ripl-thumbnails/";
        $this->fileSize = FALSE;
        $this->db = NULL;
       }
    /*This fuction creats a brand new image object*/
    private function  _construct_Image_Object($mediaType , DB_CONNECT $dbLink, $createdBy ,$base64_string){
        parent::_construct($mediaType, $dbLink, $createdBy);
        $this->filename =  parent::get_Unique_Id();
        $this->fileUrl = "gs://ripl-images/" . $this->filename. ".jpg";
        $this->thumbnUrl ="gs://ripl-thumbnails/". $this->filename . ".jpg";
        $this->fileSize= $this->base64_to_jpeg($base64_string,$this->fileUrl);
        $this->createThumbs(0.1);
        $this->db = $dbLink;
        $uniq = $this->unique_id;
        if($this->fileSize != FALSE){
            //put stuff in the database
            try {
            $query = "INSERT INTO `contentinfo`(`id`, `contentID`, `mediatype`, `creatorID`, `views`, `createdAt`, `longitude`, `latitude`)
                                               VALUES (NULL,'{$this->unique_id}','{$mediaType}','{$createdBy}','0',current_timestamp() ,'00','00' )";
            $this->pdo = $this->db ->get_PDO_Object(); ///insert record into database
            $count = $this->pdo->exec($query);
            echo $count; //echo number of affected rows
            }
            catch (PDOException $e){

                echo  "  Media Class Error:  " .$e->getMessage();

            }

        }
    }
    public function getFilePath(){
        return ($this->fileUrl);
    }

    private function base64_to_jpeg( $base64_string, $output_file ) {
        return file_put_contents($output_file, base64_decode( $base64_string));

    }

    public function sendtoAndroid($filename){
       // $url = "http://php.viralmobileapp.com/testpic.jpg";
        $imgcon = file_get_contents($filename);
        $encoded = base64_encode($imgcon);

        header('Content-Type: application/json');
        // returns {"image":"iVBORw0KGgoAAAANSUhEU...QmCC"}
        return json_encode(array('image' => $encoded));
    }
    public function createThumbs( $thumbWidth )
    {
       // $pathToImages = $this->fileUrl;
        // open the directory
        //$dir = opendir( $pathToImages );

        // loop through it, looking for any/all JPG files:
       // while (false !== ($fname = readdir( $dir ))) {
            // parse path for the extension
            $info = pathinfo($this->fileUrl);
            // continue only if this is a JPEG image
            if ( strtolower($info['extension']) == 'jpg' )
            {
               echo "\n Creating thumbnail for jpg ";

                // load image and get image size
                $img = imagecreatefromjpeg( $this->fileUrl );
                $width = imagesx( $img );
                $height = imagesy( $img );

                // calculate thumbnail size
                $new_width = $thumbWidth * $width;
                $new_height = floor( $height * ( ($thumbWidth * $width) / $width ) );

                // create a new temporary image
                $tmp_img = imagecreatetruecolor( $new_width, $new_height );

                // copy and resize old image into new image
                imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

                // save thumbnail into a file
                imagejpeg( $tmp_img, $this->thumbnUrl );
            }
       // }
        // close the directory
       // closedir( $dir );
    }
}

Class Video extends Media{
    private $fileUrl;
    function _construct($mediaType , $createdAt, $createdBy){
        parent::_construct($mediaType, $createdAt, $createdBy);
        $this->fileUrl = "gs://ripl-videos/";
    }
    public function getFilePath(){
        return ($this->fileUrl);
    }
}