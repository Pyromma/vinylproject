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

        //$conn->close();
        ?>
        <div class="search-tab">
            <div class="search-tab-params">
            <form action="" method="post">
                <h3>GENRE</h3>
                <hr>
                    <label class="container"><b>SELECT ALL</b>
                        <input onClick="toggleGenres(this)" type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                <?php
                    $s_genres = $conn->query("SELECT id, name FROM genres")->fetch_all(MYSQLI_ASSOC);
                    foreach ($s_genres as &$v) {
                    ?>
                        <label class="container"><?php echo $v['name'] ?>
                            <input name="genres[]" value="<?php echo $v['id'] ?>" type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                    <?php
                    }
                ?>
                <h3>YEARS</h3>
                <hr>
                    <?php
                        $years = $conn->query("SELECT MIN(release_year), MAX(release_year) FROM album")->fetch_row();
                    ?>
                    <table>
                        <tr>
                            <td>FROM</td>
                            <td>TO</td>
                        </tr>
                        <tr>
                            <td><input type="number" name="min-year" min="<?php echo $years[0] ?>" max="<?php echo $years[1] ?>" step="1" value="<?php echo $years[0] ?>" /></td>
                            <td><input type="number" name="max-year" min="<?php echo $years[0] ?>" max="<?php echo $years[1] ?>" step="1" value="<?php echo $years[1] ?>" /></td>
                        </tr>
                    </table>
                <h3>STYLES</h3>
                <hr>
                    <label class="container"><b>SELECT ALL</b>
                        <input onClick="toggleStyles(this)" type="checkbox" checked="checked">
                        <span class="checkmark"></span>
                    </label>
                <?php
                    $s_styles = $conn->query("SELECT id, name FROM styles")->fetch_all(MYSQLI_ASSOC);
                    foreach ($s_styles as &$v) {
                    ?>
                        <label class="container"><?php echo $v['name'] ?>
                            <input name="styles[]" value="<?php echo $v['id'] ?>" type="checkbox" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                    <?php
                    }
                ?>
            </div>
            <div class="search-tab-result">
                <div id="search-tab-search">
                    <div id="search-tab-search-container">
                        <input type="text" name="search-text" placeholder="Search.."></input>
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div id="search-tab-result-results">

                </div>
            </div>
            </form>

            <?php
                $search_album_title = "";
                $search_artist_name = "";
                $search_genres = "";
                $search_styles = "";

                $search_values = array();
                $keywords = explode(' ', trim($_POST['search-text']));
                foreach ($keywords as &$k) {
                    array_push($search_values, "%".$k."%");
                    $search_album_title .= "album.title LIKE ? OR ";
                }
                $search_album_title = substr($search_album_title, 0, strlen($search_album_title) - 3);
                foreach ($keywords as &$k) {
                    array_push($search_values, "%".$k."%");
                    $search_artist_name .= "artists.name LIKE ? OR ";
                }
                $search_artist_name = substr($search_artist_name, 0, strlen($search_artist_name) - 3);
                array_push($search_values, $_POST['min-year'], $_POST['max-year']);
                foreach ($_POST["genres"] as &$k) {
                    array_push($search_values, $k);
                    $search_genres .= ",?";
                }
                foreach ($_POST["styles"] as &$k) {
                    array_push($search_values, $k);
                    $search_styles .= ",? ";
                }
                $sql_search =
                "
                SELECT DISTINCT tempB.id FROM album_styles
                INNER JOIN
                (SELECT DISTINCT tempA.id FROM album_genres 
                INNER JOIN 
                (SELECT DISTINCT album.id FROM album_artists 
                INNER JOIN album ON album_artists.album_id = album.id 
                INNER JOIN artists ON album_artists.artist_id = artists.id
                WHERE ".$search_album_title."
                OR ".$search_artist_name."
                AND album.release_year > ? AND album.release_year < ?) tempA
                ON tempA.id=album_genres.album_id
                WHERE album_genres.genre_id IN (0".$search_genres.") ) tempB
                ON tempB.id=album_styles.album_id
                WHERE album_styles.style_id IN (0".$search_styles.");
                ";

                $search_album_title = "";
                $search_artist_name = "";
                $search_genres = "";
                $search_styles = "";

                $stmt = $conn->prepare($sql_search);
                $stmt->execute($search_values);

                $row = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                foreach ($row as $v) {
                    echo $v['id'].'<br>';
                }

                //foreach ($search_values as $v) {
                //    echo $v.'<br>';
                //}
                echo sizeof($row);

            ?>

        </div>
    </div>
</body>

<?php
    include "footer.php";
?>