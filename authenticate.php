<?php

    include "db.php";

    session_start();

    if ( !isset($_POST['email'], $_POST['password']) ) {
        $_SESSION['message'] = 'Please fill both fields';
        header("Location: login.php");
    }
    
    if( isset($_GET['logout']) ){
        session_start();
        session_destroy();
        header('Location: login.php');
    }
    
    if(isset($_POST['login'])){
        if ($stmt = $conn->prepare('SELECT id, password FROM users WHERE email = ?')) {
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password);
                $stmt->fetch_row();
    
                if (password_verify($_POST['password'], $password)) {
                    session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['id'] = $id;
                    if(isset($_SESSION['loggedin'])) header('Location: home.php');
                } else {
                    $_SESSION['message'] = 'Incorrect login credentials';
                    header("Location: login.php");
                }
            } else {
                $_SESSION['message'] = 'Incorrect login credentials';
                header("Location: login.php");
            }
        }
        unset($_POST['login']);
    }
    
    if(isset($_POST['signup']))
    {
        if ($stmt = $conn->prepare('SELECT id FROM users WHERE email = ?')) 
        {
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();
    
            if ($stmt->num_rows == 0)
            {
                $stmtIn = $conn->prepare('INSERT INTO users(email, password) VALUES ( ? , ? )');
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmtIn->bind_param('ss', $_POST['email'], $password);
                $stmtIn->execute();
                $stmtIn->close();
                $_SESSION['message'] = 'Signed up!';
                header("Location: home.php");
            }
            else 
            {
                $_SESSION['message'] = 'User already exists!';
                header("Location: login.php");
            }
        }
        unset($_POST['signup']);
    }
?>