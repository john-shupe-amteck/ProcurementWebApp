<?php
  include('php/config.php');
  
  if (is_null($_SESSION['user_id'])) [
    header("Location: index.php")
  ];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php
      if ($_SESSION['theme'] == "light") {
        echo '<link rel="stylesheet" href="css/light-theme/main.css">';
        echo '<link rel="stylesheet" href="css/light-theme/user-page.css">';
      } elseif ($_SESSION['theme'] == "dark") {
        echo '<link rel="stylesheet" href="css/dark-theme/main.css">';
        echo '<link rel="stylesheet" href="css/dark-theme/user-page.css">';
      }
    ?>

    <link rel="icon" href="img/amtecklogo.PNG">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

    <title>Amteck Procurement</title>
  </head>


  <body>
    <header class="top-nav">
      <a class="home-link" href="index.php">Amteck Procurement</a>

      <div class="dropdown" style="float:right;">
        <a href="#" class="dropbtn"><?php echo $_SESSION['user_name']?></a>
        <div class="dropdown-content">
          <a href="index.php">Logout</a>
          <a href="#">Link 2</a>
          <a href="#">Link 3</a>
        </div>
      </div>

      <?php
        if ($_SESSION['is_admin']) {
          echo '<div class="dropdown" style="float:right;">
                  <a href="#" class="dropbtn">Admin</a>
                  <div class="dropdown-content">
                    <a href="#">Add User</a>
                    <a href="#">Link 2</a>
                    <a href="#">Link 3</a>
                  </div>
                </div>';
        }
      ?>

      <a class="link" id="news" href="news.php">News</a>
      <a class="link" id="contact" href="contact.php">Contact</a>
      <a class="link" id="about" href="about.php">About</a>
    </header>


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
                      <td>'. $row["ID"] . '</td>
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
                          <input type="submit" id="'. $row["ID"] .'" value="'. $row["name"] .'">
                        </form>
                      </td>
                    </tr>';
            }
            echo "</tbody>
                </table>";        
          ?>
        </div>


        <div class="content-container" id="options">
          <form action="user-page.php" method="get">

            <?php 
            if (isset($_GET['job'])){
              echo '
              <input type="checkbox"
                name="job"
                value="'. $_GET["job"] .'"
                checked
                hidden>
              ';
            }
            if (isset($_GET['job'])) {
              echo '
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

            ?>
          </form>
        </div>
      </div>


      <div class="content-container" id="data">
          <?php
          $con = mysqli_connect("localhost", "root", "", "procurement-web-app");
          if (isset($_GET['job'])) {
            $job = $_GET['job'];
            $job_name = $_GET['job_name'];

            if ($_GET['code'] != "Select Sort Code") {
              // Query with sort code filter
              $query = "SELECT
                budgeted.descrip   as name,
                budgeted.quantity  as budqty,
                budgeted.unit      as budunit,
                budgeted.cost      as budcost,
                purchased.quantity as poqty,
                purchased.unit     as pounit,
                purchased.cost     as pocost,
                budgeted.quantity - purchased.quantity as variance
                FROM (
                  SELECT
                    itemID,
                    max(description) as descrip,
                    sum(quantity) as quantity,
                    max(`cost-unitID`) as unit,
                    avg(`unit-cost`) as cost
                  FROM 
                    `budget-details`
                  WHERE
                    `budget-details`.`budgetID` IN(
                      SELECT
                        budgets.ID
                      FROM
                        budgets
                      WHERE
                        budgets.jobID = '". $job ."'
                  ) and `budget-details`.`sort-codeID`=".$_GET['code']."
                  GROUP BY
                    itemID
                  ) as budgeted, (
                  SELECT
                    itemID,
                    sum(quantity) as quantity,
                    max(`cost-unitID`) as unit,
                    avg(`unit-cost`) as cost
                  FROM
                    `purchase-details`
                  WHERE
                    jobID = '".$job."' and `purchase-details`.`sort-codeID`=".$_GET['code']."
                  GROUP BY 
                    itemID
                  ) as purchased
                WHERE budgeted.itemID = purchased.itemID
                ORDER BY budgeted.quantity DESC
              ";
            } else {
              // Query without sort code filter
              $query = "SELECT
                          budgeted.descrip   as name,
                          budgeted.quantity  as budqty,
                          budgeted.unit      as budunit,
                          budgeted.cost      as budcost,
                          purchased.quantity as poqty,
                          purchased.unit     as pounit,
                          purchased.cost     as pocost,
                          budgeted.quantity - purchased.quantity as variance
                        FROM (
                          SELECT
                            itemID,
                            max(description) as descrip,
                            sum(quantity) as quantity,
                            max(`cost-unitID`) as unit,
                            avg(`unit-cost`) as cost
                          FROM 
                            `budget-details`
                          WHERE
                            `budget-details`.`budgetID` IN(
                              SELECT
                                budgets.ID
                              FROM
                                budgets
                              WHERE
                                budgets.jobID = '". $job ."'
                          )
                          GROUP BY
                            itemID
                          ) as budgeted, (
                              SELECT
                                itemID,
                                sum(quantity) as quantity,
                                max(`cost-unitID`) as unit,
                                avg(`unit-cost`) as cost
                              FROM
                                `purchase-details`
                              WHERE
                                jobID = '".$job."'
                                GROUP BY 
                                itemID
                          ) as purchased
                        WHERE budgeted.itemID = purchased.itemID
                        ORDER BY budgeted.quantity DESC
                        LIMIT 50";
            }

            $result = mysqli_query($con, $query);
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
            while ($row = mysqli_fetch_array($result)) {
              echo "
                <tr>
                  <td class='item-name       monospace'>".$row['name']."</td>
                  <td class='budget-quantity monospace'>".number_format($row['budqty'])."</td>
                  <td class='budget-cost     monospace'>$".number_format($row['budcost'],2) ."/". $row['budunit']."</td>
                  <td class='po-quantity     monospace'>".number_format($row['poqty'])."</td>
                  <td class='po-cost         monospace'>$".number_format($row['pocost'],2) ."/". $row['pounit']."</td>";
              if ($row['variance']>0){
                echo "
                    <td class='variance        monospace'>".number_format($row['variance'])."</td>
                  </tr>";
              } elseif ($row['variance']<0) {
                echo "
                    <td class='variance        monospace' style='color:red'>".number_format($row['variance'])."</td>
                  </tr>";
              }
            }
            echo "
              </tbody>
            </table>";
          }
          ?>
      </div>
    </main>


    <footer class="bottom-nav">
      <p>Copyright 2020</p>
    </footer>
    <script src="app.js"></script>
    <?php
    echo '<script>document.getElementById("'.$_GET['job'].'").style.backgroundColor = "gray"</script>';
    ?>
  </body>
</html>