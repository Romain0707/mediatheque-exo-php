<?php 
    session_start();
    if(isset($_SESSION['username'])) {
        $sessionUsername = $_SESSION['username'];
    }
    $bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8','root','');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul class="">
                <?php if(isset($sessionUsername)) : ?>
                    <li class="welcome">Bonjour <?= $sessionUsername ?></li>
                <?php endif; ?>
                <li class="<?php  if(!isset($sessionUsername)) { echo 'default';}?>"><a href="index.php">Accueil</a></li>
                <?php if(isset($sessionUsername)) : ?>
                    <li><a href="createfilm.php">Ajouter un film</a></li>
                <?php endif; ?>
                <li><a href="film.php">Les films</a></li>
                <?php if(!isset($sessionUsername)) : ?>
                    <li class="default"><a href="connexion.php">Connexion</a></li>
                <?php else : ?>
                    <li><a href="deconnexion.php?address=index.php">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>