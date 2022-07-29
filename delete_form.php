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
        <title>Delete Products</title>
    </head>
    <body>
    <table>
            <thead>
                <tr>
                    <th>Find product info to delete:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <form action='response.php' method="POST">
                        <input type="text" name="searchtext" id="searchtext" placeholder="Find..." />
                        <input type="submit" name="submit" id="submit" value="Go"/>
                        </form>
                    </td>
                </tr>
    </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th>Delete Product ID:</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>
                        <form action='delete.php' method="POST">
                        <input type="text" id="productID" name="productID"><br>
                        <input type="submit" id="submit" name="submit" value="Delete"><br>
                        </form>
                    </td>
                </tr>
        </tbody>
    </table>
    </body>
    <br>
    <br>
</html>