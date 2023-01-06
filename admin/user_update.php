<?php

    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['administrator'] == false) {
        exit();
    }

    include "../db.php";

    $columnID = $_POST["column"];
    $input = $_POST["inputVal"];
    $user_id = $_POST["user_id"];

    $column = '';

    switch ($columnID){
        case 'id':
            $column = 'id';
            break;
        case 'email':
            $column = 'email';
            break;
        case 'name':
            $column = 'name';
            break;
        case 'surname':
            $column = 'surname';
            break;
        case 'phone':
            $column = 'phone';
            break;
        case 'administrator':
            $column = 'administrator';
            break;
        default:
            echo 'Something went wrong';
            throw new Exception("Wrong column name");
    }

    try {
        $sql = "UPDATE users SET ".$column."=? WHERE id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $input, $user_id);
    
        $stmt->execute();

        echo $column." updated to '".$input."' succesfully.";
    }
    catch (Exception $e){
        echo 'Something went wrong <br>';
        echo 'Message: '.$e->getMessage();
    }

?>