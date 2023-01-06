<?php
    include "header.php";
?>

<body>
    <!--Navigate bar -->
    <div class="mainbar" id="navbar">
        <a id="navburger" href="javascript:void(0);" onclick="navBar()"><img id="minilogo" src="/vinylproject/images/page/navburger.png"></a>
        <a href="home.php"><img id="minilogo" src="/vinylproject/images/page/logo3.png"></a>
        <a class="navButton" href="home.php">Shop</a>
        <a class="navButton" href="">Library</a>
        <div id="navButtonUser"><img src="/vinylproject/images/page/userIcon.png"><?php echo "&nbsp".($_SESSION['nickname']); ?>
            <div class="dropdown-content">
                <a class="navButton" href="#">Edit profile</a>
                <a class="navButton" href="#">Add funds</a>
                <a class="navButton" href="/vinylproject/authenticate.php?logout=true">Logout</a>
            </div>
        </div>
    </div>

    <!--Slider-->
    <div class="slider">
        <a class="sliderButton" style="float:left;" onclick="plusSlider(-1)">&#10094;</a>

        <?php
            $con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

            $result = $con->query("SELECT games_banner.banner, games.title, games.description FROM games_banner INNER JOIN games ON games_banner.gameid = games.id");

            while($row = $result->fetch_assoc()) { ?>

            <div class="slider-panel">
                <img class="slider-img" src="images/banners/<?php echo $row['banner'] ?>">
                <div class="slider-content">
                    <h1> <?php echo $row['title'] ?> </h1>
                    <p> <?php echo $row['description'] ?> </p>
                </div>
            </div>

            <?php }

            $con->close();
        ?>

        <a class="sliderButton" style="float:right;" onclick="plusSlider(1)">&#10095;</a>
    </div>

    <!--Games-->

    <div class="games-tab">

    <?php
        $con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

        $result = $con->query("SELECT * FROM games ORDER BY rand()");

        while($row = $result->fetch_assoc()) { ?>
            <div class="game-min">
                <img src="images/covers/<?php echo $row['picture'] ?>">
                <div>
                    <h2><?php echo $row['title'] ?></h2>
                    <p><?php echo $row['price']."$" ?></p>
                </div>
            </div>
            
        <?php }

        $con->close();
    ?>
</body>
<script src="js.js"></script>

<?php
    include "footer.php";
?>