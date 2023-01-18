<?php

session_start();

    if (!isset($_SESSION['loggedin'])) {
        exit();
    }

    include "db.php";

    $date_from = $_POST["date_from"];
    $date_to = $_POST["date_to"];
    $medium_id = $_POST["medium_id"];
    //$_SESSION['id']

    $sql = "INSERT INTO rentals_active (medium_id, user_id, date_from, date_to, status) VALUES ('".$medium_id."','".$_SESSION['id']."','".$date_from."','".$date_to."','reservation')";

    if ($conn->query($sql) === TRUE) {
        echo "Successfully reserved ".$medium_id.". From ".$date_from." - ".$date_to;
      } else {
        echo "Error: Couldnt reserve ".$medium_id;
      }


?>