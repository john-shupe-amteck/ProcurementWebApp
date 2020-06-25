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

        </div>
      </div>


      <div class="content-container" id="data">
          <?php
          $con = mysqli_connect("localhost", "root", "", "procurement-web-app");
          if (isset($_GET['job'])) {
            $job = $_GET['job'];
            $job_name = $_GET['job_name'];

            $query = "SELECT A.description, sum(quantity) as qty, avg(`unit-cost`) as cost, `cost-unitID` as unit
                      FROM `budget-details` A 
                      WHERE A.budgetID in (
                        SELECT B.ID FROM budgets B WHERE B.jobID='". $job ."'
                      ) AND ( 
                        A.`sort-codeID` = 130
                        or
                        A.`sort-codeID` = 110
                      )
                      GROUP BY itemID ORDER BY qty DESC limit 20";
            $result = mysqli_query($con, $query);

            echo "
            <h1 style='text-align:left;'>".$job_name."</h1>
            <table>
              <thead>
                <tr>
                  <th>Item</th>
                  <th class='table-quantity'>Quantity</th>
                  <th>Cost</th>
                </tr>
              </thead>
              <tbody>";
            while ($row = mysqli_fetch_array($result)) {
              echo "
                <tr>
                  <td>". $row['description'] ."</td>
                  <td class='table-quantity'>". number_format($row['qty'])                       ."</td>
                  <td class='table-cost'>$".    number_format($row['cost'],2) ."/". $row['unit'] ."</td>
                </tr>
              ";
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