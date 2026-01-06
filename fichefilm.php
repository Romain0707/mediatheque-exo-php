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