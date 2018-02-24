<?php
/*
ini_set('default_charset','UTF-8');

$conn = mysql_connect('localhost', 'vacina', '123456'); /*local*/
//$conn = mysql_connect('127.8.170.130', 'adminqDAz5Hh', '1rwN_2FgSlws'); /*openshift*/

/*
mysql_set_charset('utf8', $conn);
mysql_select_db('vacina', $conn);
*/

define( 'MYSQL_HOST', 'localhost' );
define( 'MYSQL_USER', 'root' );
define( 'MYSQL_PASSWORD', '123456' );
define( 'MYSQL_DB_NAME', 'vacina' );

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Mysql {
    var $PDO;

    public function __construct() {

    	try {

    	    $this->PDO = new PDO( 'mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );
    		$this->PDO->exec("set names utf8");
    	    $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	}  catch (PDOException $e) {
    	    echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
    	}

    }
}   