<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}
    include "header.php";
?>

<body>
    <?php include "navbar.php" ?>

    <div class="content-tab">
    <?php
        include "db.php";
        //$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        $result = $conn->query("SELECT id, title, release_year, img FROM album ORDER BY rand() LIMIT 8");

        while($row = $result->fetch_assoc()) {
            $artists[] = array();
            $artists = $conn->query("SELECT artists.name FROM album_artists INNER JOIN artists ON album_artists.artist_id=artists.id WHERE album_id=".$row['id'])->fetch_all(MYSQLI_ASSOC);
            $genre = $conn->query("SELECT genres.name FROM album_genres INNER JOIN genres ON album_genres.genre_id=genres.id WHERE album_id=".$row['id'])->fetch_row();
            ?>
            <div class="game-min" onclick="location.href='album.php?id=<?php echo $row['id'] ?>'" style="cursor: pointer;">
                <div class="game-img">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['img']) ?>">
                </div>
                <div class="game-text">
                    <h3 title='<?php echo $row['title'] ?>'><?php echo $row['title'] ?></h3>

                    <?php
                        if(sizeof($artists) > 1)
                        {
                            $artists_formated = "";
                            foreach ($artists as &$value) {
                                $artists_formated = $artists_formated."\n".$value['name'];
                            }
                            echo "<a title='".$artists_formated."'><i>* Various Artists *</i></a>";
                        }
                        else
                        {
                            echo "<a href='https://www.youtube.com/watch?v=tTJObNueaqo&t=1991s&ab_channel=House%26Trance'>".$artists[0]['name']."</a>";
                        }
                    ?>

                    <p><?php echo $row['release_year']." | ".$genre[0] ?></p>
                </div>
            </div>
            
        <?php }

        $conn->close();
    ?>
</body>

<?php
    include "footer.php";
?>