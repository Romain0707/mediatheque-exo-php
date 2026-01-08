<?php 
    $title = 'Inscription';
    require_once __DIR__ . '/pages/header.php'; 
?>

<main>
    <form action="" method="POST">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name">
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom">
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password">
        <input type="submit" name="validate" value="S'inscrire">
    </form>

    <?php 
        if(!empty($_POST['name']) && !empty($_POST['prenom']) && !empty($_POST['password'])) {
            $name = htmlspecialchars($_POST['name']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

            $adding = $bdd->prepare('INSERT INTO user (nom, prenom, password) VALUES (?,?,?)');
            $adding->execute([$name, $prenom, $password]);
            header("location:inscdone.php");
            exit;
        } else if(isset($_POST['validate'])) {
            echo "<p>Merci de rentrer toutes les données</p>";
        }
    ?>
</main>
    
</body>
</html>