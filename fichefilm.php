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

                $request = $bdd->prepare(
                    'SELECT 
                        f.id,
                        f.titre,
                        f.duree,
                        f.synopsis,
                        f.userid,
                        f.img_path,
                        GROUP_CONCAT(DISTINCT r.nom SEPARATOR ", ") AS realisateurs,
                        GROUP_CONCAT(DISTINCT g.nom SEPARATOR ", ") AS genres
                    FROM film f
                    LEFT JOIN film_realisateur fr ON fr.film_id = f.id
                    LEFT JOIN realisateur r ON r.id = fr.realisateur_id
                    LEFT JOIN film_genre fg ON fg.film_id = f.id
                    LEFT JOIN genre g ON g.id = fg.genre_id
                    WHERE f.id = :id
                    GROUP BY f.id'
                );

                $request->execute([
                    'id' => (int)$_GET['id']
                ]);

                while($data = $request->fetch()){
                    $dureeEnHeure = date("G\h i\m\i\\n",mktime(0, $data['duree'], 0, 0, 0, 0));

                    if(isset($_POST['delete'])) {
                        $requestDelete = $bdd-> prepare('DELETE FROM film WHERE id = :id');
                        $requestDelete -> execute(['id' => $data['id']]);

                        header('location:film.php');
                    }

                    if(isset($_POST['validate'])) {
                        $filmId = $data['id'];

                        $title = htmlspecialchars($_POST['titre']);
                        $realisateursInput = htmlspecialchars($_POST['realisateur']);
                        $genresInput = htmlspecialchars($_POST['genre']);
                        $dureeInput = strtolower(htmlspecialchars($_POST['duree']));
                        $synopsis = htmlspecialchars($_POST['synopsis']);

                        // Conversion durée en minutes
                        $minutes = 0;
                        if (preg_match('/(\d+)\s*h\s*(\d+)?/', $dureeInput, $match)) {
                            $heures = (int)$match[1];
                            $mins = isset($match[2]) ? (int)$match[2] : 0;
                            $minutes = ($heures * 60) + $mins;
                        } elseif (preg_match('/(\d+)\s*h/', $dureeInput, $match)) {
                            $minutes = (int)$match[1] * 60;
                        } elseif (preg_match('/(\d+)/', $dureeInput, $match)) {
                            $minutes = (int)$match[1];
                        }

                        function splitValues($input) {
                            return array_values(array_unique(array_filter(array_map('trim', preg_split('/[,–-]+/', $input)))));
                        }

                        $realisateurs = splitValues($realisateursInput);
                        $genres = splitValues($genresInput);

                        try {
                            $bdd->beginTransaction();

                            $bdd->prepare('UPDATE film SET titre = :titre, duree = :duree, synopsis = :synopsis WHERE id = :id')
                                ->execute(['titre' => $title, 'duree' => $minutes, 'synopsis' => $synopsis, 'id' => $filmId]);

                            if($realisateurs) {
                                $placeholders = implode(',', array_fill(0, count($realisateurs), '(?)'));
                                $bdd->prepare("INSERT IGNORE INTO realisateur (nom) VALUES $placeholders")->execute($realisateurs);

                                $placeholders = implode(',', array_fill(0, count($realisateurs), '?'));
                                $stmt = $bdd->prepare("SELECT id FROM realisateur WHERE nom IN ($placeholders)");
                                $stmt->execute($realisateurs);
                                $realisateurIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                                $bdd->prepare('DELETE FROM film_realisateur WHERE film_id = ?')->execute([$filmId]);

                                $links = []; $params = [];
                                foreach($realisateurIds as $rid) {
                                    $links[] = '(?, ?)';
                                    $params[] = $filmId;
                                    $params[] = $rid;
                                }
                                if($links) {
                                    $bdd->prepare('INSERT INTO film_realisateur (film_id, realisateur_id) VALUES '.implode(',', $links))->execute($params);
                                }
                            }

                            if($genres) {
                                $placeholders = implode(',', array_fill(0, count($genres), '(?)'));
                                $bdd->prepare("INSERT IGNORE INTO genre (nom) VALUES $placeholders")->execute($genres);

                                $placeholders = implode(',', array_fill(0, count($genres), '?'));
                                $stmt = $bdd->prepare("SELECT id FROM genre WHERE nom IN ($placeholders)");
                                $stmt->execute($genres);
                                $genreIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                                $bdd->prepare('DELETE FROM film_genre WHERE film_id = ?')->execute([$filmId]);

                                $links = []; $params = [];
                                foreach($genreIds as $gid) {
                                    $links[] = '(?, ?)';
                                    $params[] = $filmId;
                                    $params[] = $gid;
                                }
                                if($links) {
                                    $bdd->prepare('INSERT INTO film_genre (film_id, genre_id) VALUES '.implode(',', $links))->execute($params);
                                }
                            }

                            $bdd->commit();
                            header('Location: fichefilm.php?id=' . $filmId);
                            exit;

                        } catch (Exception $e) {
                            $bdd->rollBack();
                            die("Erreur lors de la mise à jour : ".$e->getMessage());
                        }
                    }

                    if(isset($_POST['modify'])) {
                        echo 
                        "<div class=\"article\">
                            <div class=\"article__img\">
                                <img src=\"{$data['img_path']}\" alt=\"Image du film\">
                            </div>
                            <form action=\"\" class=\"article__content\" method=\"POST\" style=\"position: relative; display: flex; flex-direction: column; width: 100%; gap: 1rem;\"> 
                                <input type=\"text\" name=\"titre\" value=\"{$data['titre']}\" style=\"align-self: baseline; height: 3rem; border-radius: 8px; appearance: none; border: none; border: 1px solid #161925; padding: 10px 15px; font-size: 1.188rem; font-weight: 700; font-family: Roboto; \">
                                <label><strong>Réalisateur :</strong>  <input type=\"text\" name=\"realisateur\" value=\"{$data['realisateurs']}\" style=\"align-self: baseline; height: 3rem; border-radius: 8px; appearance: none; border: none; border: 1px solid #161925; padding: 10px 15px; font-size: 1rem; font-family: Roboto;\"></label>
                                <label><strong>Genre :</strong> <input type=\"text\" name=\"genre\" value=\"{$data['genres']}\" style=\"align-self: baseline; height: 3rem; border-radius: 8px; appearance: none; border: none; border: 1px solid #161925; padding: 10px 15px; font-size: 1rem; font-family: Roboto;\"></label>
                                <label><strong>Durée :</strong> <input type=\"text\" name=\"duree\" value=\"{$dureeEnHeure}\" style=\"align-self: baseline; height: 3rem; border-radius: 8px; appearance: none; border: none; border: 1px solid #161925; padding: 10px 15px; font-size: 1rem; font-family: Roboto;\"></label>
                                <label><strong>Synopsis :</strong></label>
                                <textarea name=\"synopsis\" style=\" height: 3rem; border-radius: 8px; appearance: none; border: none; border: 1px solid #161925; padding: 10px 15px; font-size: 1rem;  height: 100%; resize: none; font-family: Roboto;\">{$data['synopsis']}</textarea>";
                                if(isset($_SESSION['id'])) {
                                    if($_SESSION['id'] == $data['userid']) {
                                        echo '<div style="display: flex; gap: 5px; position: absolute; top: 0; right: 0;">
                                                <button name="validate" onmouseover="this.style.transform=\'scale(1.1)\';"  onmouseout="this.style.transform=\'scale(1)\';" style="appearance: none; display: flex; align-items: center; justify-content: center; border: none; background: none; transform: scale(1); transition: transform 0.3s ease; padding: 5px; border-radius: 50%; border: 1px solid #15c118;"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE --><path fill="#15c118" d="m9.55 15.15l8.475-8.475q.3-.3.7-.3t.7.3t.3.713t-.3.712l-9.175 9.2q-.3.3-.7.3t-.7-.3L4.55 13q-.3-.3-.288-.712t.313-.713t.713-.3t.712.3z"/></svg></button>
                                            </div>';
                                    }
                                }
                        echo "
                            </form>
                        </div>";
                        
                    } else {
                        echo 
                        "<div class=\"article\">
                            <div class=\"article__img\">
                                <img src=\"{$data['img_path']}\" alt=\"Image du film\">
                            </div>
                            <div class=\"article__content\" style=\"width: 100%;\"> 
                                <h3>{$data['titre']}</h3>
                                <p><strong>Réalisateur :</strong> {$data['realisateurs']}</p>
                                <p><strong>Genre :</strong> {$data['genres']}</p>
                                <p><strong>Durée :</strong> {$dureeEnHeure}</p>
                                <p><strong>Synopsis :</strong></p>
                                <p>{$data['synopsis']}</p>";
                                if(isset($_SESSION['id'])) {
                                    if($_SESSION['id'] == $data['userid']) {
                                        echo '<form action="fichefilm.php?id=' . $data['id'] . '" method="POST">
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
                ?>
            </div>
        </section>
    </main>
    

    
</body>
</html>