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
    </div>
</body>

<?php
    include "footer.php";
?>