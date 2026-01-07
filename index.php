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
        <section id="film">
            <div class="container">
                <h2>Les derniers films ajoutés :</h2>
                <div class="card__container">
                    <?php
                    $bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8','root','');
        
                    $request = $bdd->query(
                        'SELECT 
                            f.id,
                            f.titre,
                            f.duree,
                            f.img_path,
                            GROUP_CONCAT(DISTINCT r.nom SEPARATOR \', \') AS realisateurs,
                            GROUP_CONCAT(DISTINCT g.nom SEPARATOR \', \') AS genres
                        FROM film f
                        LEFT JOIN film_realisateur fr ON fr.film_id = f.id
                        LEFT JOIN realisateur r ON r.id = fr.realisateur_id
                        LEFT JOIN film_genre fg ON fg.film_id = f.id
                        LEFT JOIN genre g ON g.id = fg.genre_id
                        GROUP BY f.id
                        LIMIT 3;');
        
                    while($data = $request->fetch()){
                        $dureeEnHeure = date("G\h i\m\i\\n",mktime(0, $data['duree'], 0, 0, 0, 0));

                        echo 
                        "<div class=\"card\">
                            <div class=\"card__img\">
                                <img src=\"{$data['img_path']}\" alt=\"Image du film\">
                            </div>
                            <div class=\"card__content\"> 
                                <h3>{$data['titre']}</h3>
                                <p><strong>Réalisateur :</strong> {$data['realisateurs']}</p>
                                <p><strong>Genre :</strong> {$data['genres']}</p>
                                <p><strong>Durée :</strong> {$dureeEnHeure}</p>
                                <a href=\"fichefilm.php?id={$data['id']}\">Voir plus</a>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </section>        
    </main>

    


</body>
</html>