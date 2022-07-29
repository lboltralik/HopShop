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

            $sql = "select Product_name, Lead_time\n"

            . "from Out_of_stock join lt_table on Out_of_stock.Product_ID = lt_table.P_ID\n"
        
            . "order by Lead_time desc";

            $result = mysqli_query($conn, $sql);
            $chart_data="";

            while ($row = mysqli_fetch_array($result)) {
                $productname[] = $row['Product_name'];
                $leadtime[] = $row['Lead_time'];
            }
        }
?>
<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Graph</title> 
    </head>
    <body>
        <h1><form action='back_admin.php' method="POST">
                <input type="submit" name="submit" id="submit" value="Back"/>
            </form>
        </h1>
        <br>
        <div style="width:60%;hieght:20%;text-align:center">
            <h2 class="page-header" >Out of Stock Lead Time</h2>
            <div></div>
            <canvas  id="chartjs_bar"></canvas> 
        </div>    
    </body>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">
      var ctx = document.getElementById("chartjs_bar").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels:<?php echo json_encode($productname); ?>,
                        datasets: [{
                            label: 'Lead time (days)',
                            backgroundColor: 'rgb(201, 76, 76)',
                            data:<?php echo json_encode($leadtime); ?>,
                        }]
                    },
                    options: {
                           legend: {
                        display: true,
                        position: 'bottom',
 
                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: 14,
                        }
                    },
                }
                });
    </script>
</html>