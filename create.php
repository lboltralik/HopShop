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
            $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db) or die("Connection Failed:" .mysqli_connect_error());
            $product= $_POST['product'];
            $category= $_POST['category'];
            $brand= $_POST['brand'];
            $size= $_POST['size'];
            $measure= $_POST['measure'];

            $sql = 'CALL insert_product(?, ?, ?, ?, ?)';

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sssis', $product, $category, $brand, $size, $measure);
            mysqli_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

           /* foreach ($row as $r) { */
                echo "Product created successfully!";
            /*}*/

            $sql2 = 'CALL insert_inventory(?, ?, ?, ?, ?)';
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, 'sssis', $product, $category, $brand, $size, $measure);
            mysqli_execute($stmt2);

            $sql3 = 'CALL insert_leadtime(?, ?, ?, ?, ?)';
            $stmt3 = mysqli_prepare($conn, $sql3);
            mysqli_stmt_bind_param($stmt3, 'sssis', $product, $category, $brand, $size, $measure);
            mysqli_execute($stmt3);

            $sql4 = 'CALL insert_price(?, ?, ?, ?, ?)';
            $stmt4 = mysqli_prepare($conn, $sql4);
            mysqli_stmt_bind_param($stmt4, 'sssis', $product, $category, $brand, $size, $measure);
            mysqli_execute($stmt4);

}
?>
<!DOCTYPE html>
<html lang=en>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
</html>