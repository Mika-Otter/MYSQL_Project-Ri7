<?php 
 $servername = "localhost";
 $username = "root";
 $password = "";
 $dbName = "course_sql";
 $prompt = [];

 try{
    $db = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $prompt = ["succes" => "Connexion réussie"];

    if(isset($_POST["create"])) {
       $prompt = createUser($db);
    }
 } catch(PDOException $e){
        $prompt = ["error" => $e->getMessage()];
 }

 function createUser($db) {
    $fields = verifyFields($_POST);
    if(isset($fields["error"])) return $fields;
    $createSQL = $db->prepare(
        "INSERT INTO user(firstName, lastName, mail, postCode) VALUES(:firstName, :lastName, :mail, :postCode" 
    );
    $createSQL->execute([":lastName"=>$fields["lastName"],
    ":firstName"=>$fields["firstName"],
    ":mail"=>$fields["mail"],
    ":codePostal"=>$fields["postCode"]]);
return ["success"=>"Utilisateur bien créé"];
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

 ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>SQL Project</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <main>
            <h1>Liste d'utilisateurs</h1>
            <section class="addUser">
                <form method="post" id="addUser__form">
                    <input type="text" name="lastName" placeholder="Nom" />
                    <input type="text" name="firstName" placeholder="Prénom" />
                    <input type="text" name="mail" placeholder="Email" />
                    <input type="text" name="postCode" placeholder="Code postal" />
                    <button type="submit" name="create" id="addBtn">Ajouter</button>
                </form>
            </section>
            <section class="listUser">
                <table id="tableUser">
                    <tr class="tableHeader">
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th id="email">E-mail</th>
                        <th id="postCode">Code Postal</th>
                    </tr>
                </table>
            </section>
            <?php 
            if(count($prompt) > 0){  
                echo "<div class='toast-wrapper'>";
                if(isset($prompt["success"])) echo "<p class='success toast'>" . $prompt["success"] . "</p>";
                else{
                    foreach($prompt["error"] as $error){
                        echo "<p class='error toast'>$error</p>";
                    }
                }
                echo "</div>";
            }
        ?>
        </main>
    </body>
</html>
