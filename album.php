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

?>

<body>
    <?php include "navbar.php" ?>

    <div class="content-tab">

    <?php
        include "db.php";
        //$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        $result = $conn->prepare("SELECT title, release_year, img FROM album WHERE id=?");
        $result->bind_param('i', $_GET['id']);
        $result->execute();
        $row = $result->get_result()->fetch_assoc();
        echo $row['title'];
        echo "<img src='data:image/jpeg;base64,".base64_encode($row['img'])."'>"

    ?>

    </div>

</body>

<?php
    include "footer.php";
?>