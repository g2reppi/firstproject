<?php
/**
 * Created by PhpStorm.
 * User: Robert Tunyi
 * Date: 8/23/14
 * Time: 2:08 PM
 */
require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
use google\appengine\api\cloud_storage\CloudStorageTools;

  //echo "bullocks";
if (!empty($_POST) && isset($_POST["image"])) {
    //$user = $_POST['username'];
    $data = $_POST['image'];
    if (empty($_POST['image'])) {
        $response["success"] = 0;
        $response["message"] = "Did not receive a name";
        die(json_encode($response));
    } else {
        $data = $_POST['image'];
        //echo $data . " = is data";
        if(isset($_POST["picture"])){
            echo "made it  ";
           // $target_path = 'gs://ripl-images/newest_file.jpg';
           //file_put_contents($target_path,$_POST["picture"]);
            //echo $_POST["picture"];

        }

       //$options = [ 'gs_bucket_name' => 'ripl-temp' ];
       //$target_path = CloudStorageTools::createUploadUrl('/uploadhandler.php', $options);
       $url=  base64_to_jpeg($data,'gs://ripl-images/first_file.jpg');
        //echo $target_path;
        echo $url;
    }


    if (empty($_FILES['picture'])) {
        $response["success"] = 0;
        $response["message"] = "Did not receive a picture";
       //echo $_FILES['picture']['size'];
       //die(json_encode($response));
    } else {
        echo "got in";
        $file = $_FILES['picture'];
        // $target_path = dirname($_SERVER[PHP_SELF]) . '/file.jpg';
        $target_path = 'gs://ripl-images/new_file.jpg';
      //  $options = [ 'gs_bucket_name' => 'ripl-images' ];
       // $target_path = CloudStorageTools::createUploadUrl('/firstimage.jpg', $options);
        echo $_FILES['picture']['size']. "/n";
        echo is_uploaded_file($_FILES['picture']['tmp_name']);
       // ini_set('upload_max_filesize', '10M');
       // ini_set('post_max_size', '10M');
        //ini_set('max_input_time', 300);
        //ini_set('max_execution_time', 300);
        //file_put_contents($target_path,file_get_contents($_FILES['picture']['tmp_name']));
        move_uploaded_file($_FILES['picture']['tmp_name'], $target_path);

        //$ifp = fopen( $target_path, "w" )or die("Booty Cheeks, still didnt work");
        //fwrite( $ifp, file_get_contents($_FILES['picture']['tmp_name']) );
        //fclose( $ifp );
        if(is_dir($target_path)) {
            echo __DIR__ . "  The file ".  basename( $_FILES['picture']['name']).
                " has been uploaded";
        } else{
           // $response["success"] = 0;
            //$response["message"] = "Database Error. Couldn't upload file.";
            //die(json_encode($response));
        }
    }


} elseif(!empty($_POST) && isset($_POST["user"])){
    sendtoAndroid();
    // echo "\n" . "Sucess, Image is base64encoded and is in json format, please base64_decode data to view image";

}else {

    echo"something  wrong with namevalue pair on client side";

}


//if(is_uploaded_file($_FILES['picture']['tmp_name'])){
    //$options = [ 'gs_bucket_name' => 'ripl-images' ];
   // $target_path = CloudStorageTools::createUploadUrl('/firstimage.jpg', $options);
    //move_uploaded_file($_FILES['picture']['tmp_name'], $target_path);
   // echo "worked";
//}

function base64_to_jpeg( $base64_string, $output_file ) {
    //echo $base64_string;
    file_put_contents($output_file, base64_decode( $base64_string));
    //$ifp = fopen( $output_file, "wb" );
    //fwrite( $ifp, base64_decode( $base64_string) );
    //fclose( $ifp );
    //echo $output_file;
    return( $output_file );
}

function sendtoAndroid(){
    $url = "http://php.viralmobileapp.com/testpic.jpg";
    $thumbnail = file_get_contents($url);
    $encoded = base64_encode($thumbnail);

    header('Content-Type: application/json');
    // returns {"image":"iVBORw0KGgoAAAANSUhEU...QmCC"}
    echo json_encode(array('image' => $encoded));
}