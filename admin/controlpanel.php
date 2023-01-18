<?php

    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['administrator'] == false) {
        exit();
        header('Location: ../index.php');
    }

    include "header.php";
?>

<body>
    <?php include "navbar.php" ?>
    <div class="content-tab">
        <div class="info-panel-album">
            <a class="navButton" href="user.php" style="height:300px;font-size:25px;">Users</a>
            <a class="navButton" href="rentals.php" style="height:300px;font-size:25px;">Rentals</a>
        </div>
    </div>
</body>

<?php
    include "../footer.php";
?>