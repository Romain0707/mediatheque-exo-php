<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header>
        <nav>
            <ul class="">
                <?php if(isset($_SESSION['username'])) { echo "<li>Bonjour {$_SESSION['username']}</li>";}?>
                <li class="<?php  if(!isset($_SESSION['username'])) { echo 'default';}?>"><a href="index.php">Accueil</a></li>
                <?php if(isset($_SESSION['username'])) { echo "<li><a href=\"createfilm.php\">Ajouter un film</a></li>"; }; ?>
                <li><a href="film.php">Les films</a></li>
                <?php 
                if(!isset($_SESSION['username'])) {
                    echo "<li class=\"default\"><a href=\"connexion.php\">Connexion</a></li>";
                } else {
                    echo "<li><a href=\"deconnexion.php?address=index.php\">Déconnexion</a></li>";
                }?>
            </ul>
        </nav>
    </header>

    <main>
        <form action="" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password">
            <input type="submit" value="Se connecter">
            <label for="inscription"><a href="inscription.php">S'inscrire</a></label>
        </form>

        <?php 
        $bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8','root','');

        if(isset($_POST['name'], $_POST['prenom'], $_POST['password'])) {
            $name = htmlspecialchars($_POST['name']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $password = $_POST['password'];

            $requestFind = $bdd->query('SELECT id, nom, prenom, password FROM user');
            while($data = $requestFind->fetch()) {
                if($data['nom'] == $name && $data['prenom'] == $prenom && password_verify($password,$data['password'])) {
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['username'] = $data['nom'] . " " . $data['prenom'];
                    header("location:index.php");
                    exit;
                } else {
                    echo "Erreur de connexion";
                };
            } 
        } 
        ?>
    </main>
    
</body>
</html>