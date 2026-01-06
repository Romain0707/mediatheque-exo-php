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
        <div class="container">
            <?php
            $bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8','root','');

            $request = $bdd->prepare('SELECT id, titre, realisateur, genre, duree, synopsis, img_path FROM film WHERE id = :id');

            $request->execute(['id' => $_GET['id']]);

            while($data = $request->fetch()){
                if($data['img_path'] == "") {
                    echo 
                    "<div>
                        <p>{$data['titre']}</p>
                        <p>{$data['realisateur']}</p>
                        <p>{$data['genre']}</p>
                        <p>{$data['duree']}</p>
                        <p>{$data['synopsis']}</p>
                    </div>";
                } else {
                    echo 
                    "<div>
                        <img src=\"{$data['img_path']}\" alt=\"Image du film\">
                        <p>{$data['titre']}</p>
                        <p>{$data['realisateur']}</p>
                        <p>{$data['genre']}</p>
                        <p>{$data['duree']}</p>
                        <p>{$data['synopsis']}</p>
                    </div>";
                }
            }
            ?>
        </div>
    </main>
    

    
</body>
</html>