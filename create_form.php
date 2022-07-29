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
        <title>Create Product</title>
    </head>
    <body>
        <h1>Create New Product</h1>
        <form action='create.php' method="POST">
            <label for="product">Product name:
            </label><br>
            <input type="text" id="product" name="product"><br>
            <label for="category">Category:
            </label><br>
            <input type="text" id="category" name="category"><br>
            <label for="brand">Brand:
            </label><br>
            <input type="text" id="brand" name="brand"><br>
            <label for="size">Unit size:
            </label><br>
            <input type="text" id="size" name="size"><br>
            <label for="measure">Unit measure:
            </label><br>
            <input type="text" id="measure" name="measure"><br>
            <input type="submit" id="submit" name="submit" value="Create"><br>
            </form>
    </body>
    <br>
    <br>
</html>