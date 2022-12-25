<?php
    include "header.php";
?>

<body>  
<div class="panel">
    <h2>LOGIN</h2>
    <form method="POST" action="authenticate.php">
        <p>Email:</p>
        <input type="text" name="email" id="email" placeholder="Email">
        <br>
        <p>Password:</p>
        <input type="password" name="password" id="password" placeholder="Password">
        <br>
        <?php
            session_start();
            if (isset($_SESSION['loggedin']))
            {
                header('Location: home.php');
                exit();
            }
            if(isset($_SESSION['message'])){ ?>
                <p id="warning"> <?php echo $_SESSION['message']; ?> </p>
                <?php unset($_SESSION['message']);
            }
        ?>
        <input id="buttonP" name="login" type="submit" value="Log in">
        <input id="buttonP" name="signup" type="submit" value="Sign up">
    </form>
</div>
</body>

<?php
    include "footer.php";
?>