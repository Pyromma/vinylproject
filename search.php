<?php

    include "db.php";

    $search_text = $_POST["search_text"];
    $genres_arr = $_POST["genres_arr"];
    $year_min = $_POST["year_min"];
    $year_max = $_POST["year_max"];
    $styles_arr = $_POST["styles_arr"];

    $search_album_title = "";
    $search_artist_name = "";
    $search_genres = "";
    $search_styles = "";

    /*
    $total_records_per_page = 6;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    */
    $search_values = array();
    $keywords = explode(' ', trim($search_text));
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
    array_push($search_values, $year_min, $year_max);
    foreach ($genres_arr as &$k) {
        array_push($search_values, $k);
        $search_genres .= ",?";
    }
    foreach ($styles_arr as &$k) {
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
    WHERE (".$search_album_title."
    OR ".$search_artist_name.")
    AND (album.release_year >= ? AND album.release_year <= ?)) tempA
    ON tempA.id=album_genres.album_id
    WHERE album_genres.genre_id IN (0".$search_genres.") ) tempB
    ON tempB.id=album_styles.album_id
    WHERE album_styles.style_id IN (0".$search_styles.")
    LIMIT 25;"; //$offset, $total_records_per_page;";

    $search_album_title = "";
    $search_artist_name = "";
    $search_genres = "";
    $search_styles = "";

    $stmt = $conn->prepare($sql_search);
    $stmt->execute($search_values);

    $search_values = array();

    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    /*
    $result_count = sizeof($rows);
    $total_no_of_pages = ceil($result_count / $total_records_per_page);
    $second_last = $total_no_of_pages - 1;
    */

    foreach ($rows as $row) {
        $artists[] = array();
        $album = $conn->query("SELECT title, release_year, img FROM album WHERE id=".$row['id'])->fetch_assoc();
        $artists = $conn->query("SELECT artists.name FROM album_artists INNER JOIN artists ON album_artists.artist_id=artists.id WHERE album_id=".$row['id'])->fetch_all(MYSQLI_ASSOC);
        $genres = $conn->query("SELECT genres.name FROM album_genres INNER JOIN genres ON album_genres.genre_id=genres.id WHERE album_id=".$row['id'])->fetch_all(MYSQLI_ASSOC);
        ?>
            <div class="m-album-min" onclick="location.href='album.php?id=<?php echo $row['id'] ?>'" style="cursor: pointer;">
                <div class="m-album-img">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($album['img']) ?>">
                </div>
                <div class="m-album-text">
                    <h3 title='<?php echo $album['title'] ?>'><?php echo $album['title'] ?></h3>

                    <?php
                        echo "<a href='https://www.youtube.com/watch?v=tTJObNueaqo&t=1991s&ab_channel=House%26Trance'>".$artists[0]['name']."</a>";
                    ?>

                    <p><?php echo $album['release_year']." | ".$genres[0]["name"] ?></p>
                </div>
            </div>
        <?php
    }

    $conn->close();

?>