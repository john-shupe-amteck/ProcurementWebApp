<!-- Setup php -->
<?php
  include('php/config.php');
  include('php/user-page-data-query.php');

  // makes sure a user is logged in before navigating farther into the app
  if (is_null($_SESSION['user_id'])) [
    header("Location: index.php")                                                                           // redirects back to login page
  ];
  if (isset($_GET['description'])) {
    if ($_GET['description'] == "") [
      $_GET['description'] = "Partial Description"
    ];
  };
  if (isset($_GET['code'])) {
    if ($_GET['code'] == "") [
      $_GET['code'] = "Select Sort Code"
    ];
  }  
?>

<!DOCTYPE html>
<html lang="en">

<head>
<!-- assign meta values -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- php to switch between light and dark styles -->
  <?php
      if ($_SESSION['theme'] == "light") { // light styling
        echo '<link rel="stylesheet" href="css/light-theme/main.css">';
        echo '<link rel="stylesheet" href="css/light-theme/user-page.css">';
      } elseif ($_SESSION['theme'] == "dark") { // dark styling
        echo '<link rel="stylesheet" href="css/dark-theme/main.css">';
        echo '<link rel="stylesheet" href="css/dark-theme/user-page.css">';
      }
    ?>
  <!-- link to amteck logo for tab -->
  <link rel="icon" href="img/amtecklogo.PNG">
  <!-- link for googl fonts TODO: move into the main .css -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

  <title>Amteck Procurement</title>
</head>


<body>
  <!-- header contains the top navigation bar for the screen -->
  <?php include('php/header.php'); ?>

  <main class="main-area">
    <div id="options-panel">
      <!-- TODO: add a reference for new users who have no jobs to view.  -->
      <div class="content-container" id="jobs">
        <?php
            $con = mysqli_connect("localhost", "root", "", "procurement-web-app");

            $query = "SELECT A.* FROM jobs A WHERE A.ID in (SELECT B.jobID FROM permissions B WHERE B.userID=". $_SESSION['user_id'] .") ORDER BY A.ID";
            $result = mysqli_query($con, $query);


            echo "<table>
                    <thead>
                      <tr>
                        <th>Job ID</th>
                        <th>Job Name</th>
                      </tr>
                    </thead>
                    <tbody>";
            while ($row = mysqli_fetch_array($result))
            {
              echo '<tr id="'. $row["ID"] .'">
                      <td><a href="job-page.php?job='.$row["ID"].'">'. $row["ID"] . '</a></td>
                      <td>
                        <form action="user-page.php" method="get">
                          <input type="checkbox"
                            name="job"
                            value="'. $row["ID"] .'"
                            checked
                            hidden>
                          <input type="checkbox"
                            name="job_name"
                            value="'. $row['name'] .'"
                            checked
                            hidden>
                            <input type"checkbox"
                            name="code"
                            value="Select Sort Code"
                            checked
                            hidden>
                          <input type"checkbox"
                            name="description"
                            value="Partial Description"
                            checked
                            hidden>
                          <input type="submit" id="'. $row["ID"] .'" value="'. $row["name"] .'">
                        </form>
                      </td>
                    </tr>';
            }
            echo "</tbody>
                </table>";
          ?>
      </div>

      <!-- Options Div for filter inputs -->
      <div class="content-container" id="options">
        <!-- Filter -->
        <form action="user-page.php" method="get">

          <?php
            if (isset($_GET['job'])){
              echo '
              <input type="checkbox"
                name="job"
                value="'. $_GET["job"] .'"
                checked
                hidden>
              <input type="checkbox"
                name="job_name"
                value="'. $_GET["job_name"] .'"
                checked
                hidden>
              ';
            }
            if (isset($_GET['code'])) {
              echo '
              Code: <input type="text" autofocus="autofocus" onfocus="this.select()" name="code" placeholder="'. $_GET['code']. '"><br>
              ';
            } else {
              echo '
              Code: <input type="text" autofocus="autofocus" onfocus="this.select()" name="code" placeholder="Select Sort Code"><br>
              ';
            }

            if (isset($_GET['description']) && is_null($_GET['description'])) {
              echo '
              Description: <input type="text" onfocus="this.select()" name="description" placeholder="Partial Description"><br>
              ';
            } elseif (isset($_GET['description']) and !is_null($_GET['description'])) {
              echo '
              Description: <input type="text" onfocus="this.select()" name="description" placeholder="'.$_GET['description'].'"><br>
              ';
            } else {
              echo '
              Description: <input type="text" onfocus="this.select()" name="description" placeholder="Partial Description"><br>
              ';
            }

            ?>
          <input type="submit" value="Filter">
        </form>
      </div>
    </div>


    <div class="content-container" id="data">
      <?php
          if (isset($_GET['job'])) {
            $job = $_GET['job'];
            $job_name = $_GET['job_name'];

            $result = get_data_table($job, $con, $_GET['code'], $_GET['description']);

            // table header for data area
            echo "
              <h1 style='text-align:left;'>".$job_name."</h1>
              <table>
                <thead>
                  <tr>
                    <th class='item-name'>Item</th>
                    <th class='budget-quantity'>Budget Quantity</th>
                    <th class='budget-cost'>Cost</th>
                    <th class='po-quantity'>Purchased Quantity</th>
                    <th class='po-cost'>Cost</th>
                    <th class='variance'>Variance</th>
                  </tr>
                </thead>
                <tbody>";

            // print results to rows of data table
            while ($row = mysqli_fetch_array($result)) {
              echo "
                <tr>
                  <td class='item-name       monospace'>".$row['name']."</td>
                  <td class='budget-quantity monospace'>".number_format($row['budqty'])."</td>
                  <td class='budget-cost     monospace'>$".number_format($row['budcost'],2) ."/". $row['budunit']."</td>
                  <td class='po-quantity     monospace'>".number_format($row['poqty'])."</td>
                  <td class='po-cost         monospace'>$".number_format($row['pocost'],2) ."/". $row['pounit']."</td>"
              ;
              // color positive variance as normal
              if ($row['variance']>0){
                echo "
                  <td class='variance        monospace'>".number_format($row['variance'])."</td>
                </tr>"
                ;
              // color negative variance red
              } elseif ($row['variance']<0) {
                echo "
                  <td class='variance        monospace' style='color:red'>".number_format($row['variance'])."</td>
                </tr>";
              }
            }
            // close table
            echo "
              </tbody>
            </table>";
          }
          ?>  <!-- exit php -->
    </div>
  </main>


  <footer class="bottom-nav">
    <p>Copyright 2020</p>
  </footer>
  <script src="app.js"></script>
  <!-- php to color the active job button a different color -->
  <?php
    echo '<script>document.getElementById("'.$_GET['job'].'").style.backgroundColor = "gray"</script>';
    ?>
</body>

</html>