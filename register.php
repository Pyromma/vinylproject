<?php
    include "header.php";
?>

<body>  
<div class="register-panel">
    <h2>REGISTRATION</h2>
    <form method="POST" action="authenticate_register.php">
        <table>
            <tr>
                <td>Email:</td>
                <td><input type="text" name="email" id="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name" placeholder="Name"></td>
            </tr>
            <tr>
                <td>Last Name:</td>
                <td><input type="text" name="lastname" placeholder="Last Name"></td>
            </tr>
            <tr>
                <td>Telephone number:</td>
                <td><input type="tel" name="phone" placeholder="Phone number" pattern="[0-9]{9,15}"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" id="password" placeholder="Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,20}$"></td>
            </tr>
            <tr>
                <td>Confirm password:</td>
                <td><input type="password" name="password_confirm" id="password_confirm" placeholder="Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,20}$"></td>
            </tr>
        </table>

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
        <input id="buttonP" name="signup" type="submit" value="Sign up"><br>
        <a href="login.php">Log in</a>
        <!--<input id="buttonP" name="signup" type="submit" value="Sign up">-->
    </form>
</div>
</body>

<?php
    include "footer.php";
?>