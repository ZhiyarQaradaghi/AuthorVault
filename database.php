<?php 
$host =
'localhost'; 
$dbname =
'library'; 
$user =
'root'; 
$pass = '1234'; 
try{
$pdo = new PDO('mysql:host='. $host .';dbname='. $dbname, $user, $pass);
}
catch (PDOException $e){
throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>

