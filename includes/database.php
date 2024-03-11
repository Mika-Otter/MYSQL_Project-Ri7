<?php 

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "course_sql";

try{
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $prompt = ["succes" => "Connexion réussie"];

} catch(PDOException $e){
        $prompt = ["error" => $e->getMessage()];
 }


?>