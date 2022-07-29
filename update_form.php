<?php
    $price = "";
    $leadtime = "";
    $inventory = "";
    $productID = "";
    $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $cleardb_server = $cleardb_url["host"];
    $cleardb_username = $cleardb_url["user"];
    $cleardb_password = $cleardb_url["pass"];
    $cleardb_db = substr($cleardb_url["path"],1);
    $active_group = 'default';
    $query_builder = TRUE;

    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['submit'])) {
        /* $conn= mysqli_connect('localhost', 'root', '', 'hopshop') */
        $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db) or die("Connection Failed:" .mysqli_connect_error());
        if(isset($_GET['searchtext'])) {
            $searchtext= $_GET['searchtext'];

            $sql = 'CALL search_updateinfo(?)';

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 's', $searchtext);
            mysqli_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $productID = $row['Product_ID'];
                $price = $row['Wholesale_price'];
                $leadtime = $row['Lead_time'];
                $inventory = $row['Inventory'];
            }
        }
    }
?>

<!DOCTYPE html>
<html lang=en>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=devise-width, initial-scale=1.0">
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
        <title>Update Product</title>
    </head>
    <body style="background-color: #c5e7e0">
        <h1><form action='back_admin.php' method="POST">
                <input type="submit" name="submit" id="submit" value="Back"/>
            </form>
        </h1>
        <br>
    <table style="margin: 0px auto;">
            <thead>
                <tr>
                    <th>Product finder:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <form action="" method="GET">
                        <input type="text" name="searchtext" id="searchtext" placeholder="Find..." />
                        <input type="submit" name="submit" id="submit" value="Go"/>
                        </form>
                    </td>
                </tr>
    </tbody>
    </table>
    <div>
    <table style="float: left">
        <thead>
            <tr>
                <th>Current Information: <?php echo "Product ID $productID"; ?></th>
            </tr>
        </thead>
        <tbody>
        <form action="" method="GET">
                <tr>
                    <th style="border-bottom: none">Current price:</th>
                    <td> <?php echo "$price"; ?> </td>
                </tr>
                <tr>
                    <th style="border-bottom: none">Current lead time:</th>
                    <td> <?php echo "$leadtime"; ?> </td>
                </tr>
                <tr>
                    <th style="border-bottom: none">Current inventory:</th>
                    <td> <?php echo "$inventory"; ?> </td>
                </tr>
        </form>
        </tbody>
    </table>
    <table style="float: left">
        <thead>
            <tr>
                <th>Update Information:</th>
            </tr>
        </thead>
        <tbody>
            <form action='update.php' method="POST">
                <tr>
                    <th style="border-bottom: none">Updating ID:</th>
                    <td>
                        <input type="number" id="productID" name="productID"/>
                    </td>
                </tr>
                <tr>
                    <th style="border-bottom: none">Update price to:</th>
                    <td>
                        <input type="number" id="price" name="price"/>
                    </td>
                </tr>
                <tr>
                    <th style="border-bottom: none">Update lead time to:</th>
                    <td>
                        <input type="number" id="leadtime" name="leadtime"/>
                    </td>
                </tr>
                <tr>
                    <th style="border-bottom: none">Update inventory to:</th>
                    <td>
                        <input type="number" id="inventory" name="inventory"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" id="submit" name="submit" value="Update"/>
                    </td>
                </tr>
                </form>
        </tbody>
    </table>
    </div>
    </body>
    <br>
    <br>
</html>