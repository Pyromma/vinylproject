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
        array_push($search_values, "%".$k."%","%".$k."%","%".$k."%","%".$k."%","%".$k."%");
        $search_users .= "id LIKE ? OR email LIKE ? OR name LIKE ? OR surname LIKE ? OR phone LIKE ? OR ";
    }
    $search_users = substr($search_users, 0, strlen($search_users) - 3);


    $no_of_records_per_page = 10;

    $result = $conn->prepare("SELECT COUNT(id) FROM users WHERE ".$search_users.";");
    $result->execute($search_values);
    $total_rows = $result->get_result()->fetch_row()[0];
    $total_pages = ceil($total_rows / $no_of_records_per_page);
    if ($pageno > $total_pages) $pageno = $total_pages;
    if ($pageno < 1 ) $pageno = 1;
    $offset = ($pageno-1) * $no_of_records_per_page;

    $sql_search = "SELECT id, email, name, surname, phone FROM users WHERE ".$search_users." LIMIT ".$offset.", ".$no_of_records_per_page;

    $search_users = "";

    $stmt = $conn->prepare($sql_search);
    $stmt->execute($search_values);

    $search_values = array();

    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    echo "<table>
    <tr>
        <th>ID</th>
        <th>EMAIL</th>
        <th>NAME</th>
        <th>SURNAME</th>
        <th>PHONE</th>
        <th>ACTION</th>
    </tr>";

    foreach ($rows as $row) {
        echo
        "<tr>
            <td width='10%'>".$row['id']."</td>
            <td width='30%'>".$row['email']."</td>
            <td width='15%'>".$row['name']."</td>
            <td width='15%'>".$row['surname']."</td>
            <td width='10%'>".$row['phone']."</td>
            <td width='20%'><a href='user.php?id=". $row['id'] ."'><button name='view_user' value='view_user'>VIEW</button></td></a>
        </tr>";
    }

    echo "</table>";

    echo "<input type='hidden' id='curr_page' value=".$pageno." >";
    echo "<input type='hidden' id='max_page' value=".$total_pages." >";
    echo "<input type='hidden' id='results_count' value=".$total_rows." >";

    $conn->close();

?>