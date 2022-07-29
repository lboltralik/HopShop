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
        <title>Motown Hop Shop</title>
    </head>
    <body>
        <div style="clear: both">
        <h1 style="float: left">Morgantown Hop Shop</h1>
        <h2 style="float: right"><form action='login.php' method="POST">
                <input type="submit" name="submit" id="submit" value="Admin"/>
            </form>
        </h2>
    </div>
        <table>
            <thead>
                <tr>
                    <th>Search Products:</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><form action='response.php' method="POST">
                            <input type="text" name="searchtext" id="searchtext" placeholder="Search..." />
                            <input type="submit" name="submit" id="submit" value="Go"/>
                        </form></td>
                    <td>
                        <img src="hops.jpg" alt="Hops" width="1050" height="100"/>
                    </td>
                </tr>
            </tbody>
        </table>

    </body>
    <br>
    <br>
</html>
<?php
    $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $cleardb_server = $cleardb_url["host"];
    $cleardb_username = $cleardb_url["user"];
    $cleardb_password = $cleardb_url["pass"];
    $cleardb_db = substr($cleardb_url["path"],1);
    $active_group = 'default';
    $query_builder = TRUE;

    /* $conn= mysqli_connect('localhost', 'root', '', 'hopshop') */
    $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db) or die("Connection Failed:" .mysqli_connect_error());
    $text = '';

    $sql = 'CALL search_product(?)';

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $text);
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
?>
