<!DOCTYPE html>
<html lang=en>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <style> 
            input[type=submit] {
            background-color: #8D918D;
            border: none;
            color: white;
            padding: 6px 16px;
            text-decoration: none;
            margin: 4px 2px;
            cursor: pointer;
            font-family: Verdana, Arial, sans-serif;
        }
        </style>
        <body>
        <h1><form action='back_admin.php' method="POST">
                <input type="submit" name="submit" id="submit" value="Back"/>
            </form>
        </h1>
    </body>
</html>
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
            $productID= $_POST['productID'];

            $sql = 'CALL delete_product(?)';

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $productID);
            mysqli_execute($stmt);

            echo "Product has been deleted.";
        }
?>