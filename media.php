<?php
/**
 * Created by PhpStorm.
 * User: Robert Tunyi
 * Date: 8/30/14
 * Time: 3:58 PM
 */
abstract class  Media {
    private $mediaType; // these are extentions could be jpeg etc
    private $createdAt;
    private $createdBy;
    protected $mediaId;
    function _construct($mediaType , $createdAt, $createdBy){
        $this->mediaType =$mediaType;
        $this->createdAt =$createdAt;
        $this->createdBy = $createdBy;
    }
    abstract public function getFilePath();

}

Class Image extends Media{
    private $fileUrl; //url to image folder
    function _construct($mediaType , $createdAt, $createdBy){
        parent::_construct($mediaType, $createdAt, $createdBy);
        $this->fileUrl = "gs://ripl-images/";
    }
    public function getFilePath(){
        return ($this->fileUrl);
    }

    public function base64_to_jpeg( $base64_string, $output_file ) {
        //echo $base64_string;
        file_put_contents($output_file, base64_decode( $base64_string));
        //$ifp = fopen( $output_file, "wb" );
        //fwrite( $ifp, base64_decode( $base64_string) );
        //fclose( $ifp );
        //echo $output_file;
        return( $output_file );
    }

    public function sendtoAndroid(){
        $url = "http://php.viralmobileapp.com/testpic.jpg";
        $thumbnail = file_get_contents($url);
        $encoded = base64_encode($thumbnail);

        header('Content-Type: application/json');
        // returns {"image":"iVBORw0KGgoAAAANSUhEU...QmCC"}
        echo json_encode(array('image' => $encoded));
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