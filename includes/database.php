<?php 

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "course_sql";
$prompt = [];

try{
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->beginTransaction();
    $prompt = ["success" => "Connexion réussie"];

        if(isset($_POST["create"])) {
            $prompt = createUser($db);    }

    $sql = $db -> prepare("SELECT * FROM user");
    $sql->execute();
    $tableauRequete = $sql -> fetchAll();
    $db->commit();
} catch(PDOException $e){
        $prompt = ["error" => $e->getMessage()];
 }

 function createUser($db) {
    $fields = verifyFields($_POST);
        if(isset($fields["error"])) return $fields;
    $createSQL = $db -> prepare("INSERT INTO user VALUES(:firstName, :lastName, :mail, :postCode)");
    $createSQL -> execute([
        ':firstName' => $fields['firstName'],
        ':lastName' => $fields['lastName'],
        ':mail' => $fields['mail'],
        ':postCode' => $fields['postCode']
    ]);
    
    $db->commit();

    $sql = $db->prepare("SELECT * FROM user");
    $sql->execute();
    return ["success" => "User correctly added!", "tableauRequete" => $sql->fetchAll()];
}

function verifyFields($fields){
    $goodFields = [];
    $prompts = ["error"=>[]];
    foreach($fields as $field=>$value){
        switch($field){
            case "lastName" :
                $regex = "/^[a-z\-]+$/i";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Mauvais nom");
                break;
            case "firstName" :
                $regex = "/^[a-z\-]+$/i";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Mauvais prénom");
                break;
            case "mail" :
                $regex = "/^[A-zÀ-ÿ0-9]*@[a-z]*\.[a-z]{2,5}$/";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Veuillez rentrer un email valide");
                break;
            case "postCode" :
                $regex = "/^[0-9]{5}$/";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Mauvais code postal, veuillez rentrer 5 chiffres");
                break;
        }
        $goodFields[$field] = htmlspecialchars($value);
    }
    return count($prompts["error"]) > 0 ? $prompts : $goodFields;
}
