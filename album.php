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
    $result = $result->get_result();
    if ($result->num_rows == 0)
    {
        header("Location: home.php");
    }
    $album = $result->fetch_assoc();

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

    $result = $conn->prepare("SELECT id, format, state_carrier, state_cover FROM mediums WHERE album_id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $mediums = $result->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
                <div id="search-tab-result-results"></div>
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
                            echo "<td style='width:35%'>".$medium['format']."</td>";
                            echo "<td style='width:15%'><div class='state_container'><div class='state_value' style='width:".$width_carrier."%;background-color:hsl(".$bgcolor_carrier.", 80%, 50%)'>".$medium['state_carrier']."</div></div></td>";
                            echo "<td style='width:15%'><div class='state_container'><div class='state_value' style='width:".$width_cover."%;background-color:hsl(".$bgcolor_cover.", 80%, 50%)'>".$medium['state_cover']."</div></div></td>";
                            echo "<td style='width:25%'><input type='text' name='datefilter' value='' id='date-".$medium['id']."' /><button id='btn-".$medium['id']."' name='reserve' value='".$medium['id']."'>RESERVE</button></td>";
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
<script type="text/javascript">

function convertToDate(dateString) {
    let d = dateString.split("-");
    let dat = new Date(d[0] + '/' + d[1] + '/' + d[2]);
    return dat;     
}
$(document).ready(function() {

    $(function() {

        <?php
        include "db.php";
        foreach ($mediums as &$medium) {
            $result = $conn->query("SELECT date_from, date_to FROM rentals_active WHERE medium_id=".$medium['id']);
            $dates = $result->fetch_all(MYSQLI_ASSOC);
            echo "unavailable_dates".$medium['id']." = [";
            foreach ($dates as $key=>&$dates) {
                echo "[convertToDate('".$dates['date_from']."'), convertToDate('".$dates['date_to']."')]";
                if(sizeof($dates) != $key) echo ", 
                ";
            }
            echo "];
            ";
            echo "$('input[id=";
            echo '"date-'.$medium['id'].'"';
            echo "]').daterangepicker({
                autoUpdateInput: false,
                'minDate': new Date(),
                'maxDate': new Date(new Date().setDate(new Date().getDate() + 60)),
                'maxSpan': {
                    'days': 14
                },
                isInvalidDate: function(date) 
                {
                    var possD = new Date(8640000000000000);
                    for (let index = 0; index < unavailable_dates".$medium['id'].".length; ++index) {
                        if(this.startDate < unavailable_dates".$medium['id']."[index][0] && possD >= unavailable_dates".$medium['id']."[index][0]){
                            possD = unavailable_dates".$medium['id']."[index][0];
                        }
                    }
                    for (let index = 0; index < unavailable_dates".$medium['id'].".length; ++index) {
                        if(date >= unavailable_dates".$medium['id']."[index][0] && date <= unavailable_dates".$medium['id']."[index][1]){
                            return true;
                        }
    
                        if(date >= possD && !this.endDate){
                            return true;
                        }
                    }
                },
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY'
                }
            });
            ";
            ?>
            var start;
            var end;
            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                start = picker.startDate.format('YYYY-MM-DD');
                end = picker.endDate.format('YYYY-MM-DD');
            });
            <?php
            echo "var start;";
            echo "var end;";
            echo "$('#";
            echo 'btn-'.$medium['id'];
            echo "').click(function() {
                $('input[name=";
                echo '"datefilter"';
                echo "]').on('apply.daterangepicker', function(ev, picker) {
                    start = picker.startDate.format('DD-MM-YYYY');
                    end = picker.endDate.format('DD-MM-YYYY');
                });
                //console.log(start);
                $.post('insert_reservation.php', {
                            date_from: start,
                            date_to: end,
                            medium_id: $(this).attr('value')
                        }, function(data) {
                            $('#search-tab-result-results').html(data);
                            alert(data);
                            location.reload();
                        });
            });";
        }
        ?>

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });
});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>

.daterangepicker .calendar-table {
    background-color: var(--dark8);
    border: 1px solid rgba(0, 0, 0, 0.2);
}

.daterangepicker td.off, .daterangepicker td.off.start-date, .daterangepicker td.off.end-date {
    background-color: var(--dark8);
}
.daterangepicker td.in-range {
    background-color: var(--green7);

}
.daterangepicker {
    background-color: var(--dark8);
    color: white;
    font-size: 19px;
    line-height: 1em;
    border: 2px solid var(--green1);
}
.daterangepicker td.active, .daterangepicker td.active:hover {
    background-color: var(--green1);
    border-color: transparent;
    color: var(--dark2);
}

.daterangepicker:before, .daterangepicker:after {
    border-bottom-color: rgba(0, 0, 0, 0.2);
}
.daterangepicker .drp-buttons .btn {
    margin-left: 8px;
    height: 40px;
    width: 20%;
    font-size: 18px;
    font-weight: bold;
}
.daterangepicker .drp-buttons {
    border-top: 1px solid rgba(0, 0, 0, 0.2);
    height: 50px;
}
.daterangepicker td.disabled {
    background-color: rgba(255, 0, 0, 0.2);
    color: rgb(255, 128, 128);
}
</style>
<?php
    include "footer.php";
?>