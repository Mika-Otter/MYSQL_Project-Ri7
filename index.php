

<?php 
include 'includes/database.php';
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
            <h1>user_management</h1>
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
                    <?php 
                      $update = isset($_POST["update"]) ? $_POST["update"] : -1;
                      $confirm = isset($_POST["confirm"]) && isset($prompt["error"]) ? $_POST["confirm"] : -1;
                        foreach($tabRequest as $entry){ ?>
                            <tr>
                                <form method="post" class="update__form">
                                    <?php
                                      if($update != $entry["ID"]) : ?>
                                        <td><?php echo $entry["lastName"]; ?></td>
                                        <td><?php echo $entry["firstName"]; ?></td>
                                        <td><?php echo $entry["mail"]; ?></td>
                                        <td><?php echo $entry["postCode"]; ?></td>
                                        <td>
                                        <div class="btn__ctn">
                                            <button type="submit" name="updateBtn" id="updateBtn" value="<?= $entry["ID"] ?>">Update</button>
                                            <button type="submit" id="deleteBtn" name="delete" value="<?= $entry["ID"] ?>">X</button>
                                        </div>
                                <?php else : ?>
                                    <td><input type="text" name="lastName" value="<?= $entry["lastName"]; ?>" class="update__input"/></td>
                                    <td><input type="text" name="firstName" value="<?= $entry["firstName"]; ?>" class="update__input"/></td>
                                    <td><input type="text" name="mail" value="<?= $entry["mail"]; ?>" class="update__input"/></td>
                                    <td><input type="text" name="postCode" value="<?= $entry["postCode"]; ?>" class="update__input"/></td>
                                    <td>
                                    <div class="btn__ctn">
                                        <button type="submit" id="confirmBtn" name="confirm" value="<?= $entry["ID"] ?>">Confirm</button>
                                        <button type="submit" id="deleteBtn" name="delete" value="<?= $entry["ID"] ?>">X</button>
                                    </div>
                                </td>
                                        <?php endif; ?>
                                
                                    </form>

                            </tr>
                    <?php } ?>
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
