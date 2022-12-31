<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}
    include "header.php";
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {

        $("#search-albums").click(function() {
            console.log($("[name=search-text]").val());
            $.post("search.php", {
                search_text: $("[name='search-text']").val(),
                genres_arr: $("[name^='genres']:checked").map(function (idx, ele) {
                    return $(ele).val();
                }).get(),
                year_min: $("[name='year-min']").val(),
                year_max: $("[name='year-max']").val(),
                styles_arr: $("[name^='styles']:checked").map(function (idx, ele) {
                    return $(ele).val();
                }).get()
                
            }, function(data) {
                $("#search-tab-result-results").html(data);
            });
        });
        $("#search-albums").trigger('click');
    });
</script>

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
            <div class="p-album-min" onclick="location.href='album.php?id=<?php echo $row['id'] ?>'" style="cursor: pointer;">
                <div class="p-album-img">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['img']) ?>">
                </div>
                <div class="p-album-text">
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
                            <td><input type="number" name="year-min" min="<?php echo $years[0] ?>" max="<?php echo $years[1] ?>" step="1" value="<?php echo $years[0] ?>" /></td>
                            <td><input type="number" name="year-max" min="<?php echo $years[0] ?>" max="<?php echo $years[1] ?>" step="1" value="<?php echo $years[1] ?>" /></td>
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
                        <button type="submit" id="search-albums"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div id="search-tab-result-results">

                </div>
            </div>
        </div>
    </div>
</body>

<?php
    include "footer.php";
?>