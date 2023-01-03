<?php

    session_start();

    if(!isset($_GET['id']))
    {
        header("Location: home.php");
    }

    if (!isset($_SESSION['loggedin'])) {
        header('Location: index.php');
        exit();
    }

    include "header.php";

    include "db.php";
    //$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    $result = $conn->prepare("SELECT title, release_year, img FROM album WHERE id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $album = $result->get_result()->fetch_assoc();

    $result = $conn->prepare("SELECT artists.id, artists.name FROM album_artists INNER JOIN artists ON album_artists.artist_id=artists.id WHERE album_id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $artists = $result->get_result()->fetch_all(MYSQLI_ASSOC);

    $result = $conn->prepare("SELECT genres.name FROM album_genres INNER JOIN genres ON album_genres.genre_id=genres.id WHERE album_id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $genres = $result->get_result()->fetch_all(MYSQLI_ASSOC);

    $result = $conn->prepare("SELECT styles.name FROM album_styles INNER JOIN styles ON album_styles.style_id=styles.id WHERE album_id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $styles = $result->get_result()->fetch_all(MYSQLI_ASSOC);

    $result = $conn->prepare("SELECT tracklist.lp, tracklist.song, tracklist.duration FROM tracklist WHERE album_id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $tracklist = $result->get_result()->fetch_all(MYSQLI_ASSOC);

    $result = $conn->prepare("SELECT id, state_carrier, state_cover FROM mediums WHERE album_id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $mediums = $result->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<body>
    <?php include "navbar.php" ?>

    <div class="content-tab">
        <div class="switchable-tab">
            <button class="switchable-tab-button" onclick="openAlbumTab(event, 'tracklist')" id="defaultTab">INFORMATION</button>
            <button class="switchable-tab-button" onclick="openAlbumTab(event, 'rentals')">RENTALS</button>
        </div>
        <div class="info-panel-album">
            <div class="info-panel-album-cover">
                <img src='data:image/jpeg;base64,<?php echo base64_encode($album['img'])?>'>
            </div>
            <div class="info-panel-album-details">
                <table>
                    <tr>
                        <td colspan="2"><h2><?php echo $album['title']?></h2></td>
                    </tr>
                    <tr>
                        <td style="width:30%">Artists</td>
                        <td>
                        <?php
                            foreach ($artists as $key=>&$artist) {
                                echo "<a href='artist.php?id=".$artist['id']."'>".$artist["name"]."</a>";
                                if(sizeof($artists)-1 != $key) echo ", ";
                            }
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Release year</td>
                        <td><?php echo $album['release_year']?></td>
                    </tr>
                    <tr>
                        <td>Genres</td>
                        <td>
                        <?php
                            foreach ($genres as $key=>&$genre) {
                                echo $genre["name"];
                                if(sizeof($genres)-1 != $key) echo ", ";
                            }
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Styles</td>
                        <td>
                        <?php
                            foreach ($styles as $key=>&$style) {
                                echo $style["name"];
                                if(sizeof($styles)-1 != $key) echo ", ";
                            }
                        ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;width: 100%;">
                <hr>
            </div>
            <div class="album-tabs" id="tracklist">
                <div class="tracklist">
                <h3>TRACKLIST</h3>
                    <table>
                        <tr>
                            <th>LP.</th>
                            <th>Song title</th>
                            <th>Duration</th>
                        </tr>
                        <?php
                        foreach ($tracklist as &$track) {
                            echo "<tr>";
                            echo "<td style='width:10%'>".$track['lp']."</td>";
                            echo "<td style='width:75%'>".$track['song']."</td>";
                            echo "<td style='width:15%'>".$track['duration']."</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="album-tabs" id="rentals">
                <div class="carriers">
                <h3>RENTALS</h3>
                    <table>
                        <tr>
                            <th>Carrier's ID.</th>
                            <th>Format</th>
                            <th>Carrier's state</th>
                            <th>Cover's state</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        foreach ($mediums as &$medium) {
                            $width_carrier = $medium['state_carrier'] * 20;
                            $bgcolor_carrier = $medium['state_carrier'] * 23;
                            $width_cover = $medium['state_cover'] * 20;
                            $bgcolor_cover = $medium['state_cover'] * 23;
                            echo "<tr>";
                            echo "<td style='width:10%'>".$medium['id']."</td>";
                            echo "<td style='width:35%'></td>";
                            echo "<td style='width:15%'><div class='state_container'><div class='state_value' style='width:".$width_carrier."%;background-color:hsl(".$bgcolor_carrier.", 80%, 50%)'>".$medium['state_carrier']."</div></div></td>";
                            echo "<td style='width:15%'><div class='state_container'><div class='state_value' style='width:".$width_cover."%;background-color:hsl(".$bgcolor_cover.", 80%, 50%)'>".$medium['state_cover']."</div></div></td>";
                            echo "<td style='width:25%'><button name='signup' value='RENT'>RENT</button><button name='signup' value='RESERVE'>RESERVE</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
<script>
     document.getElementById("defaultTab").click();
</script>

<?php
    include "footer.php";
?>