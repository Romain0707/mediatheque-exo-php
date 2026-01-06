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
        <section id="article-film">
            <div class="container">
                <?php
                $bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8','root','');

                $request = $bdd->prepare('SELECT * FROM film WHERE id = :id');

                $request->execute(['id' => $_GET['id']]);

                while($data = $request->fetch()){
                    $dureeEnHeure = date("G\h i\m\i\\n",mktime(0, $data['duree'], 0, 0, 0, 0));

                    if($data['img_path'] == "") {
                        echo 
                        "<div class=\"article\">
                            <div class=\"article__content\"> 
                                <h3>{$data['titre']}</h3>
                                <p>{$data['realisateur']}</p>
                                <p>{$data['genre']}</p>
                                <p>{$dureeEnHeure}</p>
                                <p><strong>Synopsis :</strong></p>
                                <p>{$data['synopsis']}</p>
                            </div>
                        </div>";
                    } else {
                        echo 
                        "<div class=\"article\">
                            <div class=\"article__img\">
                                <img src=\"{$data['img_path']}\" alt=\"Image du film\">
                            </div>
                            <div class=\"article__content\"> 
                                <h3>{$data['titre']}</h3>
                                <p><strong>Réalisateur :</strong> {$data['realisateur']}</p>
                                <p><strong>Genre :</strong> {$data['genre']}</p>
                                <p><strong>Durée :</strong> {$dureeEnHeure}</p>
                                <p><strong>Synopsis :</strong></p>
                                <p>{$data['synopsis']}</p>";
                                if(isset($_SESSION['id'])) {
                                    if($_SESSION['id'] == $data['userid']) {
                                        echo '<form action="" method="POST">
                                                <button name="modify"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE --><path fill="#f4a434" d="m9.25 22l-.4-3.2q-.325-.125-.612-.3t-.563-.375L4.7 19.375l-2.75-4.75l2.575-1.95Q4.5 12.5 4.5 12.338v-.675q0-.163.025-.338L1.95 9.375l2.75-4.75l2.975 1.25q.275-.2.575-.375t.6-.3l.4-3.2h5.5l.4 3.2q.325.125.613.3t.562.375l2.975-1.25l2.75 4.75l-2.575 1.95q.025.175.025.338v.674q0 .163-.05.338l2.575 1.95l-2.75 4.75l-2.95-1.25q-.275.2-.575.375t-.6.3l-.4 3.2zm2.8-6.5q1.45 0 2.475-1.025T15.55 12t-1.025-2.475T12.05 8.5q-1.475 0-2.488 1.025T8.55 12t1.013 2.475T12.05 15.5"/></svg></button>
                                                <button name="delete"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE --><path fill="#8d1111" d="M7 21q-.825 0-1.412-.587T5 19V6H4V4h5V3h6v1h5v2h-1v13q0 .825-.587 1.413T17 21zM17 6H7v13h10zM9 17h2V8H9zm4 0h2V8h-2zM7 6v13z"/></svg></button>
                                            </form>';
                                    }
                                }
                        echo "
                            </div>
                        </div>";
                    }
                }

                if(isset($_POST['modify'])) {

                }
                ?>
            </div>
        </section>
    </main>
    

    
</body>
</html>