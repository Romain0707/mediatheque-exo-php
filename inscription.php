<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php require_once __DIR__ . '/pages/header.php'; ?>

    <main>
        <form action="" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password">
            <input type="submit" value="S'inscrire">
        </form>

        <?php 
        $bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8','root','');
        if(isset($_POST['name'], $_POST['prenom'], $_POST['password'])) {
            $name = htmlspecialchars($_POST['name']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

            $adding = $bdd->prepare('INSERT INTO user (nom, prenom, password) VALUES (?,?,?)');
            $adding->execute([$name, $prenom, $password]);
            header("location:inscdone.php");
        } else {
            echo "<p>Merci de rentrer des données</p>";
        }
        ?>
    </main>
    
</body>
</html>