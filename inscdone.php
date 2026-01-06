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
        <p>Votre inscription est validé !</p>
    </main>
    
</body>
</html>