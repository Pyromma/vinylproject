<?php
    include "header.php";
?>

<body>  
<div class="panel">
    <h2>LOGIN</h2>
    <form method="POST" action="authenticate.php">
        <p>Email:</p>
        <input type="email" name="email" id="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
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
        <input id="buttonP" name="login" type="submit" value="Log in"><br>
        <!--<input id="buttonP" name="signup" type="submit" value="Sign up">-->
        <a href="register.php">Register</a>
    </form>
</div>
</body>

<?php
    include "footer.php";
?>