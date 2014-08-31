<?php
/**
 * Created by PhpStorm.
 * User: Robert Tunyi
 * Date: 8/23/14
 * Time: 12:05 PM
 */
/**
 * A class file to connect to database
 */
class DB_CONNECT {
    protected static $db; //points to instance os PDO
    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
    }

    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }

    /**
     * Function to connect with database
     */
    protected  function connect() {
        // import database connection variables
        require_once __DIR__ . '/db_config.php';
        $conn = NULL;
        try{
            $conn = new PDO('mysql:unix_socket='.DB_SERVER .';dbname='.DB_DATABASE, DB_USER, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            echo 'ERROR: ' . $e->getMessage();
        }
        Self::$db = $conn;


        // Connecting to mysql database
        //$con = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());

        // Selecing database
        //$db = mysql_select_db(DB_DATABASE) or die(mysql_error()) or die(mysql_error());

        // returing connection cursor
        return Self::$db;
    }

    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
        mysql_close();
    }

}