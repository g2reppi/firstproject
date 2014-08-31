<?php
/**
 * Created by PhpStorm.
 * User: Robert Tunyi
 * Date: 8/23/14
 * Time: 8:06 PM
 */

$gs_name = $_FILES['uploaded_files']['tmp_name'];
move_uploaded_file($gs_name, 'gs://ripl-images/testfile.jpg');
//echo "hit";