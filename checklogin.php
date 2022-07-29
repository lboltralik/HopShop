<?php
    $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
    $cleardb_server = $cleardb_url["host"];
    $cleardb_username = $cleardb_url["user"];
    $cleardb_password = $cleardb_url["pass"];
    $cleardb_db = substr($cleardb_url["path"],1);
    $active_group = 'default';
    $query_builder = TRUE;
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        /* $conn= mysqli_connect('localhost', 'root', '', 'hopshop') */
            $conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db) or die("Connection Failed:" .mysqli_connect_error());
            $username= $_POST['username'];
            $password= $_POST['password'];

            $sql = 'CALL checkuser(?, ?)';

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
            mysqli_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            foreach ($row as $r) {
                if (!empty($r)) {
                    header("Location: admin.php");
                }
                else {
                    header("Location: login.php");
                }
            }
    }
?>