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
            $prompt = createUser($db);    
        } 
        elseif(isset($_POST["confirm"])){
                $prompt = updateUser($db);
        } 
        elseif (isset($_POST["delete"])){
                $prompt = deleteUser($db);
        };

    $sql = $db -> prepare("SELECT * FROM user");
    $sql->execute();
    $tabRequest = $sql -> fetchAll();

    $db->commit();
} catch(PDOException $e){
        $prompt = ["error" => $e->getMessage()];
        echo $e->getMessage();
 }

 function createUser($db) {
    $fields = verifyFields($_POST);
        if(isset($fields["error"])) return $fields;
    $createSQL = $db -> prepare("INSERT INTO user(firstName, lastName, mail, postCode) VALUES(:firstName, :lastName, :mail, :postCode)");
    $createSQL -> execute([
        ':firstName' => $fields['firstName'],
        ':lastName' => $fields['lastName'],
        ':mail' => $fields['mail'],
        ':postCode' => $fields['postCode']
    ]);
}

function updateUser($db){
    $fields = verifyFields($_POST);
    if(isset($fields["error"])) return $fields;
    $updateSQL = $db -> prepare("UPDATE user SET lastName=:lastName, firstName=:firstName, mail=:mail, postCode=:postCode WHERE ID=:ID");
    $updateSQL->execute([":ID"=>$fields["confirm"],
                        ":lastName"=>$fields["lastName"],
                        ":firstName"=>$fields["firstName"],
                        ":mail"=>$fields["mail"],
                        ":postCode"=>$fields["postCode"]]);
    return ["success"=>"User modified !"];
}

function deleteUser($db) {
    if(isset ($_POST["delete"])){
        $deleteSQL = $db -> prepare("DELETE FROM user WHERE ID=:ID");
        $deleteSQL -> execute([":ID" => $_POST["delete"]]);
        return ["success" => "User deleted !"];
    }
}

function verifyFields($fields){
    $goodFields = [];
    $prompts = ["error"=>[]];
    foreach($fields as $field=>$value){
        switch($field){
            case "lastName" :
                $regex = "/^[a-z\-]+$/i";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Wrong lastname...");
                break;
            case "firstName" :
                $regex = "/^[a-z\-]+$/i";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Wrong firstname...");
                break;
            case "mail" :
                $regex = "/^[A-zÀ-ÿ0-9]*@[a-z]*\.[a-z]{2,5}$/";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Please enter a valid e-mail");
                break;
            case "postCode" :
                $regex = "/^[0-9]{5}$/";
                if(!preg_match($regex,$value)) array_push($prompts["error"],"Please enter a correct 5-digit postcode ! ");
                break;
        }
        $goodFields[$field] = htmlspecialchars($value);
    }
    return count($prompts["error"]) > 0 ? $prompts : $goodFields;
}
