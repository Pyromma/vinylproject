<?php

    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['administrator'] == false) {
        exit();
    }

    if (isset($_POST['pageno'])) {
        $pageno = $_POST['pageno'];
    } else {
        $pageno = 1;
    }

    include "../db.php";

    $search_text = $_POST["search_text"];

    $search_users = "";

    $search_values = array();
    $keywords = explode(' ', trim($search_text));
    foreach ($keywords as &$k) {
        array_push($search_values, "%".$k."%","%".$k."%","%".$k."%","%".$k."%","%".$k."%","%".$k."%","%".$k."%","%".$k."%","%".$k."%");
        $search_users .= "rentals_active.id LIKE ? OR rentals_active.medium_id LIKE ? OR mediums.album_id LIKE
         ? OR album.title LIKE ? OR rentals_active.user_id LIKE ? OR users.email LIKE
         ? OR rentals_active.date_from LIKE ? OR rentals_active.date_to LIKE ? OR rentals_active.status LIKE ? OR ";
    }
    $search_users = substr($search_users, 0, strlen($search_users) - 3);


    $no_of_records_per_page = 10;

    $result = $conn->prepare("SELECT COUNT(rentals_active.id)
    FROM rentals_active INNER JOIN users ON rentals_active.user_id=users.id 
    INNER JOIN mediums ON rentals_active.medium_id=mediums.id
    INNER JOIN album ON mediums.album_id=album.id WHERE ".$search_users.";");
    $result->execute($search_values);
    $total_rows = $result->get_result()->fetch_row()[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);
    if ($pageno > $total_pages) $pageno = $total_pages;
    if ($pageno < 1 ) $pageno = 1;
    $offset = ($pageno-1) * $no_of_records_per_page;

    $sql_search = "SELECT rentals_active.id, rentals_active.medium_id, mediums.album_id, album.title,
    rentals_active.user_id, users.email, rentals_active.date_from, 
    rentals_active.date_to, rentals_active.status 
    FROM rentals_active INNER JOIN users ON rentals_active.user_id=users.id 
    INNER JOIN mediums ON rentals_active.medium_id=mediums.id
    INNER JOIN album ON mediums.album_id=album.id WHERE ".$search_users." LIMIT ".$offset.", ".$no_of_records_per_page;

    $search_users = "";

    $stmt = $conn->prepare($sql_search);
    $stmt->execute($search_values);

    $search_values = array();

    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    echo "<table>
    <tr>
        <th>ID</th>
        <th>MEDIUM ID</th>
        <th>ALBUM ID</th>
        <th>TITLE</th>
        <th>USER ID</th>
        <th>EMAIL</th>
        <th>DATE FROM</th>
        <th>DATE TO</th>
        <th>STATUS</th>
        <th>ACTION</th>
    </tr>";

    foreach ($rows as $row) {
        echo
        "<tr>
            <td width='5%'>".$row['id']."</td>
            <td width='5%'>".$row['medium_id']."</td>
            <td width='5%'>".$row['album_id']."</td>
            <td width='20%'>".$row['title']."</td>
            <td width='5%'>".$row['user_id']."</td>
            <td width='10%'>".$row['email']."</td>
            <td width='10%'>".$row['date_from']."</td>
            <td width='10%'>".$row['date_to']."</td>";
            if($row['status']=='overdue') echo "<td style='color:#FF0000'";
            elseif($row['status']=='reservation') echo "<td style='color:#99FFFF'";
            elseif($row['status']=='rental') echo "<td style='color:#FFFF99'";
            else echo "<td";
            echo " width='10%' class='rental_status'>".strtoupper($row['status'])."</td>
            <td width='10%'><a href='rental.php?id=". $row['id'] ."'><button name='view_user' value='view_user'>VIEW</button></td></a>
        </tr>";
    }

    echo "</table>";

    echo "<input type='hidden' id='curr_page' value=".$pageno." >";
    echo "<input type='hidden' id='max_page' value=".$total_pages." >";
    echo "<input type='hidden' id='results_count' value=".$total_rows." >";

    $conn->close();

?>