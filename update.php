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
        if(!empty($_POST['productID'])) {
            $productID = $_POST['productID'];
            
            if(!empty($_POST['price'])) {
                $newprice = $_POST['price'];

                $sql = 'CALL update_wholesale(?, ?)';

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'id', $productID, $newprice);
                mysqli_execute($stmt);

                $sql2 = 'CALL profit_update()';

                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2);
                mysqli_execute($stmt2);

                echo nl2br("Price has been updated.\n");
            }
            
            if(!empty($_POST['leadtime'])) {
                $newleadtime= $_POST['leadtime'];

                $sql = 'CALL update_leadtime(?, ?)';

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ii', $productID, $newleadtime);
                mysqli_execute($stmt);

                echo nl2br("Lead time has been updated.\n");  
            }

            if(!empty($_POST['inventory'])) {
                $newinventory= $_POST['inventory'];

                $sql = 'CALL update_inv(?, ?)';

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ii', $productID, $newinventory);
                mysqli_execute($stmt);

                echo nl2br("Inventory has been updated. \n");
            }
    }
}   
?>
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
        <h1 style="float: left"><form action='back.php' method="POST">
                <input type="submit" name="submit" id="submit" value="Back"/>
            </form>
        </h1>
    </body>
</html>