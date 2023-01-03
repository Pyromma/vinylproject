<?php

    include "db.php";

    session_start();
    
    if(isset($_POST['signup']))
    {
        if(isset($_POST['email'], $_POST['password'], $_POST['password_confirm']))
        {
            if ($stmt = $conn->prepare('SELECT id FROM users WHERE email = ?'))
            {
                $stmt->bind_param('s', $_POST['email']);
                $stmt->execute();
                $stmt->store_result();
        
                if ($stmt->num_rows == 0)
                {
                    if($_POST['password'] == $_POST['password_confirm'])
                    {
                        $stmtIn = $conn->prepare("INSERT INTO users(email, name, surname, phone, password) VALUES ( ? , NULLIF(?, '') , NULLIF(?, '') , NULLIF(?, ''), ?)");
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $stmtIn->bind_param('sssss', $_POST['email'], $_POST['name'], $_POST['lastname'], $_POST['phone'], $password);
                        $stmtIn->execute();
                        $stmtIn->close();
                        $_SESSION['message'] = 'Signed up. You can now log in';
                        header("Location: home.php");
                    }
                    else
                    {
                        $_SESSION['message'] = "Passwords don't match";
                        header("Location: register.php");
                    }
                }
                else 
                {
                    $_SESSION['message'] = 'User with given email address already exists';
                    header("Location: register.php");
                }
            }
        }
        else
        {
            $_SESSION['message'] = 'Please fill required fields';
            header("Location: register.php");
        }
        
        unset($_POST['signup']);
    }
?>