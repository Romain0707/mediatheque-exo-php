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
                <input type="file" name="img" id="img">
                <input type="submit" value="Ajouter">
            </form>

            <?php 
            $bdd = new PDO('mysql:host=localhost;dbname=mediatheque;charset=utf8','root','');

            if(isset($_POST['title'], $_POST['realise'], $_POST['genre'], $_POST['duree'], $_POST['synopsis'])) {
                $title = htmlspecialchars($_POST['title']);
                $realise = htmlspecialchars($_POST['realise']);
                $genre = htmlspecialchars($_POST['genre']);
                $duree = htmlspecialchars($_POST['duree']);
                $synopsis = htmlspecialchars($_POST['synopsis']);
                $tmpName = $_FILES['img']['tmp_name'];
                $originalName = $_FILES['img']['name'];
                $imageInfo = getimagesize($tmpName);
                $resultUserId = $bdd->prepare('SELECT id FROM user WHERE id = :id');
                $resultUserId -> execute(['id' => $_SESSION['id']]);
                $userId = $resultUserId->fetch();
                var_dump($userId);

                if ($imageInfo !== false) {

                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                    $newName = uniqid("img_", true) . "." . $extension;

                    $destination = "assets/img/" . $newName;

                    move_uploaded_file($tmpName, $destination);

                } else {
                    $destination = "";
                }
                $adding = $bdd->prepare('INSERT INTO film (titre, realisateur, genre, duree, synopsis, img_path, userid) 
                                                VALUES (:title, :realisateur, :genre, :duree, :synopsis, :imgpath, :userid)');
                $adding->execute(['title' => $title,
                                        'realisateur' => $realise,
                                        'genre' => $genre, 
                                        'duree' => $duree,
                                        'synopsis' => $synopsis, 
                                        'imgpath' => $destination,
                                        'userid' => $userId['id']]);
            } else {
                echo "<p>Merci de rentré des données</p>";
            }
            ?>
        </div>
    </main>

    
</body>
</html>