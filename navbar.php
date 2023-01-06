<div class="mainbar" id="navbar">
    <a id="navburger" href="javascript:void(0);" onclick="navBar()"><img id="minilogo" src="/vinylproject/images/page/navburger.png"></a>
    <a href="/vinylproject/home.php"><img id="minilogo" src="/vinylproject/images/page/logo3.png"></a>
    <a class="navButton" href="/vinylproject/home.php">Home</a>
    <a class="navButton" href="">Library</a>
    <?php
        if($_SESSION['administrator'] == true)
            echo "<a class='navButton' href='#'>Admin panel</a>";
    ?>
    <div id="navButtonUser"><img src="/vinylproject/images/page/userIcon.png"><?php echo "&nbsp".($_SESSION['email']); ?>
        <div class="dropdown-content">
            <a class="navButton" href="#">Profile</a>
            <a class="navButton" href="#">Rentals</a>
            <a class="navButton" href="/vinylproject/authenticate.php?logout=true">Logout</a>
        </div>
    </div>
</div>
<script src="/vinylproject/js.js"></script>