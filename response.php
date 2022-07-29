<?php
    $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $cleardb_server = $cleardb_url["host"];
    $cleardb_username = $cleardb_url["user"];
    $cleardb_password = $cleardb_url["pass"];
    $cleardb_db = substr($cleardb_url["path"],1);
    $active_group = 'default';
    $query_builder = TRUE;
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        /* $conn= mysqli_connect('localhost', 'root', '', 'hopshop') */
        $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db)or die("Connection Failed:" .mysqli_connect_error());
        if(isset($_POST['searchtext'])) {
            $searchtext= $_POST['searchtext'];

            $sql = 'CALL search_product(?)';

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 's', $searchtext);
            mysqli_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $str = "<table>";
            $str .= "<thead>";
            $str .= "<th>Product ID</th>";
            $str .= "<th>Category</th>";
            $str .= "<th>Brand</th>";
            $str .= "<th>Product Name</th>";
            $str .= "<th>Unit Price</th>";
            $str .= "<th>Size</th>";
            $str .= "<th>Inventory</th>";
            $str .= "<th>Out of stock</th>";
            $str .= "</thead>";
            $str .="<tbody>";

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $str .= "<tr>";
                $str .= "<td>" . $row['Product_ID'] . "</td>";
                $str .= "<td>" . $row['Category'] . "</td>";
                $str .= "<td>" . $row['Brand'] . "</td>";
                $str .= "<td>" . $row['Product_name'] . "</td>";
                $str .= "<td>" . $row['Unit_price'] . "</td>";
                $str .= "<td>" . $row['Size'] . "</td>";
                $str .= "<td>" . $row['Inventory'] . "</td>";
                $str .= "<td>" . $row['Out_of_stock'] . "</td>";
                $str .= "</tr>";
            }
            $str .="</tbody>";
            $str .= "</table>";
            echo $str;
        }
    }
?>
<!DOCTYPE html>
<html lang=en>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
</html>
