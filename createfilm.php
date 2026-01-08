<?php 
    $title = 'Créer un film';
    require_once __DIR__ . '/pages/header.php';
?>
    <main>
    <section id="form__createfilm">
        <div class="container">
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title">
                <label for="realise">Réalisateur :</label>
                <input type="text" id="realise" name="realise">
                <label for="genre">Genre :</label>
                <input type="text" id="genre" name="genre">
                <label for="duree">Durée :</label>
                <input type="number" id="duree" name="duree">
                <label for="synopsis">Synopsis :</label>
                <textarea name="synopsis" id="synopsis"></textarea>
                <label for="img">Ajoutez une image pour le film :</label>
                <label for="img" class="custom__file"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"><!-- Icon from Google Material Icons by Material Design Authors - https://github.com/material-icons/material-icons/blob/master/LICENSE --><path fill="currentColor" d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5c0-2.64-2.05-4.78-4.65-4.96M14 13v4h-4v-4H7l4.65-4.65c.2-.2.51-.2.71 0L17 13z"/></svg></label>
                <input type="file" name="img" id="img">
                <input type="submit" value="Ajouter" name="send">
<?php
    if(!empty($_POST['title']) && !empty($_POST['realise']) && !empty($_POST['genre']) && !empty($_POST['duree']) && !empty($_POST['synopsis'])) :
        $title = htmlspecialchars($_POST['title']);
        $realise = htmlspecialchars($_POST['realise']);
        $genre = htmlspecialchars($_POST['genre']);
        $duree = htmlspecialchars($_POST['duree']);
        $synopsis = htmlspecialchars($_POST['synopsis']);
        $tmpName = $_FILES['img']['tmp_name'];
        $originalName = $_FILES['img']['name'];
        $resultUserId = $bdd->prepare('SELECT id FROM user WHERE id = :id');
        $resultUserId -> execute(['id' => $_SESSION['id']]);
        $userId = $resultUserId->fetch();

        function splitValues($input) {
            return array_unique(
                array_filter(
                    array_map(
                        'trim',
                        preg_split('/[,–-]+/', $input)
                    )
                )
            );
        }

        $realisateurs = splitValues($realise);
        $genres = splitValues($genre);

        if ($tmpName != "") {

            $imageInfo = getimagesize($tmpName);
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);

            $newName = uniqid("img_", true) . "." . $extension;

            $destination = "assets/img/" . $newName;

            move_uploaded_file($tmpName, $destination);

        } else {
            $destination = "";
        }
        $adding = $bdd->prepare(
            'INSERT INTO film (titre, duree, synopsis, img_path, userid)
            VALUES (:title, :duree, :synopsis, :imgpath, :userid)'
        );

        $adding->execute([
            'title' => $title,
            'duree' => $duree,
            'synopsis' => $synopsis,
            'imgpath' => $destination,
            'userid' => $userId['id']
        ]);

        $filmId = $bdd->lastInsertId();

        $insertRealisateur = $bdd->prepare(
            'INSERT IGNORE INTO realisateur (nom) VALUES (:nom)'
        );

        $getRealisateurId = $bdd->prepare(
            'SELECT id FROM realisateur WHERE nom = :nom'
        );

        $linkFilmRealisateur = $bdd->prepare(
            'INSERT INTO film_realisateur (film_id, realisateur_id)
            VALUES (:film, :realisateur)'
        );

        foreach ($realisateurs as $nom) {
            $insertRealisateur->execute(['nom' => $nom]);
            $getRealisateurId->execute(['nom' => $nom]);
            $rid = $getRealisateurId->fetchColumn();

            $linkFilmRealisateur->execute([
                'film' => $filmId,
                'realisateur' => $rid
            ]);
        }

        $insertGenre = $bdd->prepare(
            'INSERT IGNORE INTO genre (nom) VALUES (:nom)'
        );

        $getGenreId = $bdd->prepare(
            'SELECT id FROM genre WHERE nom = :nom'
        );

        $linkFilmGenre = $bdd->prepare(
            'INSERT INTO film_genre (film_id, genre_id)
            VALUES (:film, :genre)'
        );

        foreach ($genres as $nom) {
            $insertGenre->execute(['nom' => $nom]);
            $getGenreId->execute(['nom' => $nom]);
            $gid = $getGenreId->fetchColumn();

            $linkFilmGenre->execute([
                'film' => $filmId,
                'genre' => $gid
            ]);
        }

    elseif(empty($_POST['title']) && empty($_POST['realise']) && empty($_POST['genre']) && empty($_POST['duree']) && empty($_POST['synopsis']) && isset($_POST['send'])) : ?>
        <p class="error">Entrer des données</p>
    <?php endif ?>
            </form>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/pages/footer.php'; ?>