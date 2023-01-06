<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['administrator'] == false) {
	header('Location: ../index.php');
	exit();
}

if (isset($_POST['pageno'])) {
    $pageno = $_POST['pageno'];
} else {
    $pageno = 1;
}

    include "header.php";
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        var page_no = 1;
        var search_text = $("[name='search-text']").val();
        var max_pages = 0;
        $("#search-next").click(function() {
            page_no++;
            if( page_no > max_pages) page_no = max_pages;
            $.post("../admin/search_users.php", {
                search_text: search_text,
                pageno: page_no,
            }, function(data) {
                $("#search-tab-result-results").html(data);
                $("#curr-page-info").html(page_no);
            });
        });

        $("#search-prev").click(function() {
            page_no--;
            if(page_no < 1) page_no = 1;
            if(max_pages == 0) page_no = 0;
            $.post("../admin/search_users.php", {
                search_text: search_text,
                pageno: page_no,
            }, function(data) {
                $("#search-tab-result-results").html(data);
                $("#curr-page-info").html(page_no);
            });
        });

        $("#search-users").click(function() {
            page_no = 1;
            search_text = $("[name='search-text']").val();
            $.post("../admin/search_users.php", {
                search_text: $("[name='search-text']").val(),
                pageno: page_no,
            }, function(data) {
                $("#search-tab-result-results").html(data);
                //page_no = $("#curr_page").val();
                max_pages = $("#max_page").val();
                if(max_pages == 0) page_no = 0;
                result_count = $("#results_count").val();
                $("#curr-page-info").html(page_no);
                $("#max-page-info").html(max_pages);
                $("#results-info").html(result_count);
            });
        });
        $("#search-users").trigger('click');
    });
</script>

<body>
    <?php include "../navbar.php" ?>
    <div class="content-tab">
        <div class="search-tab">
            <div class="search-tab-result"  style="width:100%">
                <div id="search-tab-search">
                    <div id="search-tab-search-container">
                        <input type="text" name="search-text" placeholder="Search.."></input>
                        <button type="submit" id="search-users"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <button type="submit" name="PREV" id="search-prev" class="search-info">PREV</button>
                <button type="submit" name="NEXT" id="search-next" class="search-info">NEXT</button>
                <table class="search-info">
                    <tr>
                        <td>Page:</td>
                        <td><span id="curr-page-info"></span> / <span id="max-page-info"></span></td>
                    </tr>
                    <tr>
                        <td>Results:</td>
                        <td id="results-info">0</td>
                    </tr>
                </table>
                <div id="search-tab-result-results">

                </div>
            </div>
        </div>
    </div>
</body>

<?php
    include "../footer.php";
?>