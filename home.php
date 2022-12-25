<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit();
}
    include "header.php";
?>

<body>
    <!--Navigate bar -->
    <div class="mainbar" id="navbar">
        <a id="navburger" href="javascript:void(0);" onclick="navBar()"><img id="minilogo" src="images/page/navburger.png"></a>
        <a href="home.php"><img id="minilogo" src="images/page/logo3.png"></a>
        <a class="navButton" href="home.php">Shop</a>
        <a class="navButton" href="">Library</a>
        <div id="navButtonUser"><img src="images/page/userIcon.png"><?php echo "&nbsp".($_SESSION['email']); ?>
            <div class="dropdown-content">
                <a class="navButton" href="#">Profile</a>
                <a class="navButton" href="#">Rentals</a>
                <a class="navButton" href="authenticate.php?logout=true">Logout</a>
            </div>
        </div>
    </div>

    <div class="games-tab">
    <?php
        include "db.php";
        //$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        $result = $conn->query("SELECT title, release_year, img FROM album ORDER BY rand() LIMIT 50");

        while($row = $result->fetch_assoc()) { ?>
            <div class="game-min">
                <div class="game-img">
                    <img src="data:image/jpeg;base64,<?php echo $row['img'] ?>">
                </div>
                <div class="game-text">
                    <h4><?php echo $row['title'] ?></h4>
                    <p><?php echo $row['release_year'] ?></p>
                </div>
            </div>
            
        <?php }

        $conn->close();
    ?>
</body>
<!--<script src="js.js"></script>-->
<?php
    include "footer.php";
?>