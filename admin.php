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
        <title>Admin Page</title>
    </head>
    <body>
        <h1>Admin Page</h1>
        <table>
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><form action='create_form.php' method="POST">
                            <input type="submit" name="submit" id="submit" value="Create"/>
                        </form></td>
                </tr>
                <tr>
                    <td><form action='update_form.php' method="POST">
                            <input type="submit" name="submit" id="submit" value="Update"/>
                        </form></td>
                </tr>
                <tr>
                    <td><form action='delete_form.php' method="POST">
                            <input type="submit" name="submit" id="submit" value="Delete"/>
                        </form></td>
                </tr>
                <tr>
                    <td><form action='lt_analysis.php' method="POST">
                            <input type="submit" name="submit" id="submit" value="Inventory/Lead Time"/>
                        </form></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>