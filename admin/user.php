<?php
session_start();

if(!isset($_GET['id']))
{
    header("Location: users.php");
}

if (!isset($_SESSION['loggedin']) || $_SESSION['administrator'] == false) {
	header('Location: ../index.php');
	exit();
}
    include "header.php";
    include "../db.php";

    $result = $conn->prepare("SELECT id, email, name, surname, phone, administrator FROM users WHERE id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();
    $result = $result->get_result();
    if ($result->num_rows == 0)
    {
        header("Location: users.php");
    }
    $user = $result->fetch_assoc();

    $result = $conn->prepare("SELECT id, medium_id, date_from, date_to, status FROM rentals_active WHERE user_id=?");
    $result->bind_param('i', $_GET['id']);
    $result->execute();

    $rentals = $result->get_result()->fetch_all(MYSQLI_ASSOC);
    
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>

    function editParam(event) {
        var id = event.parentElement.parentElement.id;
        var btnName = event.className;

        if(btnName == 'change-btn')
        {
            event.setAttribute("hidden", "hidden");
            document.getElementById(id).getElementsByClassName('safe-btn')[0].removeAttribute("hidden");
            document.getElementById(id).getElementsByClassName('cancel-btn')[0].removeAttribute("hidden");
            document.getElementById(id).getElementsByClassName('change-input')[0].type = document.getElementById(id).getElementsByClassName('change-input')[0].name;
            document.getElementById(id).getElementsByClassName('view')[0].style.display= 'none';
        }

        if(btnName == 'cancel-btn')
        {
            event.setAttribute("hidden", "hidden");
            document.getElementById(id).getElementsByClassName('safe-btn')[0].setAttribute("hidden", "hidden");
            document.getElementById(id).getElementsByClassName('change-btn')[0].removeAttribute("hidden");
            document.getElementById(id).getElementsByClassName('change-input')[0].type = "hidden";
            document.getElementById(id).getElementsByClassName('view')[0].style.display= "";
        }

        if(btnName == 'safe-btn')
        {
            var input = document.getElementById(id).getElementsByClassName('change-input')[0].value;
            
            $.post("user_update.php", {
                inputVal: input,
                column: id,
                user_id: <?php echo $_GET['id'] ?>,
            }, function(data) {
                $("#return-info").html(data);
                event.setAttribute("hidden", "hidden");
                document.getElementById(id).getElementsByClassName('cancel-btn')[0].setAttribute("hidden", "hidden");
                document.getElementById(id).getElementsByClassName('change-btn')[0].removeAttribute("hidden");
                document.getElementById(id).getElementsByClassName('change-input')[0].type = "hidden";
                document.getElementById(id).getElementsByClassName('view')[0].style.display= "";
            });
            
        }
    }
    
</script>

<body>
    <?php include "navbar.php" ?>
    <div class="content-tab">
        <div class="switchable-tab">
            <button class="switchable-tab-button" onclick="openAlbumTab(event, 'user-info')" id="defaultTab">INFORMATION</button>
            <button class="switchable-tab-button" onclick="openAlbumTab(event, 'user-rentals')">USER'S RENTALS</button>
        </div>
        <div class="info-panel-album">
            <div id="return-info"></div>
            <div class="album-tabs" id="user-info">
            <table style="width:100%;">
                <tr id="id">
                    <td><b>ID</b></td>
                    <td><span class="view"><?php echo $user['id'] ?></span><input class="change-input" type="hidden" name="text" value="<?php echo $user['id'] ?>"></input></td>
                    <td><button class='change-btn' onclick="editParam(this)">CHANGE</button><button class='safe-btn' onclick="editParam(this)" hidden>SAFE</button><button class='cancel-btn' onclick="editParam(this)" hidden>CANCEL</button></td>
                </tr>
                <tr id="email">
                    <td><b>EMAIL</b></td>
                    <td><span class="view"><?php echo $user['email'] ?></span><input class="change-input" type="hidden" name="text" value="<?php echo $user['email'] ?>"></input></td>
                    <td><button class='change-btn' onclick="editParam(this)">CHANGE</button><button class='safe-btn' onclick="editParam(this)" hidden>SAFE</button><button class='cancel-btn' onclick="editParam(this)" hidden>CANCEL</button></td>
                </tr>
                <tr id="name">
                    <td><b>NAME</b></td>
                    <td><span class="view"><?php echo $user['name'] ?></span><input class="change-input" type="hidden" name="text" value="<?php echo $user['name'] ?>"></input></td>
                    <td><button class='change-btn' onclick="editParam(this)">CHANGE</button><button class='safe-btn' onclick="editParam(this)" hidden>SAFE</button><button class='cancel-btn' onclick="editParam(this)" hidden>CANCEL</button></td>
                </tr>
                <tr id="surname">
                    <td><b>LAST NAME</b></td>
                    <td><span class="view"><?php echo $user['surname'] ?></span><input class="change-input" type="hidden" name="text" value="<?php echo $user['surname'] ?>"></input></td>
                    <td><button class='change-btn' onclick="editParam(this)">CHANGE</button><button class='safe-btn' onclick="editParam(this)" hidden>SAFE</button><button class='cancel-btn' onclick="editParam(this)" hidden>CANCEL</button></td>
                </tr>
                <tr id="phone">
                    <td><b>PHONE</b></td>
                    <td><span class="view"><?php echo $user['phone'] ?></span><input class="change-input" type="hidden" name="text" value="<?php echo $user['phone'] ?>"></input></td>
                    <td><button class='change-btn' onclick="editParam(this)">CHANGE</button><button class='safe-btn' onclick="editParam(this)" hidden>SAFE</button><button class='cancel-btn' onclick="editParam(this)" hidden>CANCEL</button></td>
                </tr>
                <tr id="administrator">
                    <td><b>ADMINISTRATOR</b></td>
                    <td><span class="view"><?php echo $user['administrator'] ?></span><input class="change-input" type="hidden" name="text" value="<?php echo $user['administrator'] ?>"></input></input></td>
                    <td><button class='change-btn' onclick="editParam(this)">CHANGE</button><button class='safe-btn' onclick="editParam(this)" hidden>SAFE</button><button class='cancel-btn' onclick="editParam(this)" hidden>CANCEL</button></td>
                </tr>
            </table>
            </div>
            <div class="album-tabs" id="user-rentals">
                <table class="generic-table" style="width:100%;">
                    <tr>
                        <th>ID</th>
                        <th>Medium's ID</th>
                        <th>Date from</th>
                        <th>Date to</th>
                        <th>Status</th>
                    </tr>
                    <?php
                        foreach($rentals as $rental){
                            echo "<tr>";
                            echo "<td>".$rental['id']."</td>";
                            echo "<td>".$rental['medium_id']."</td>";
                            echo "<td>".$rental['date_from']."</td>";
                            echo "<td>".$rental['date_to']."</td>";
                            echo "<td>".$rental['status']."</td>";
                            echo "</tr>";
                        }
                    ?>

                </table>
            </div>
        </div>
    </div>
</body>
<script>
     document.getElementById("defaultTab").click();
</script>

<?php
    include "../footer.php";
?>