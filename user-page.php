<?php
  include('php/config.php');
  session_start();
  if (is_null($_SESSION['user_id'])) [
    header("Location: index.php")
  ]
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/user-page.css">

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
          echo '<div class="dropdown" style="float:right;">';
          echo '  <a href="#" class="dropbtn">Admin</a>';
          echo '  <div class="dropdown-content">';
          echo '    <a href="#">Add User</a>';
          echo '    <a href="#">Link 2</a>';
          echo '    <a href="#">Link 3</a>';
          echo '  </div>';
          echo '</div>';
        }
      ?>

      <a class="link" id="news" href="news.php">News</a>
      <a class="link" id="contact" href="contact.php">Contact</a>
      <a class="link" id="about" href="about.php">About</a>
    </header>


    <main class="main-area">
      <div class="content-container jobs">     
        <?php
          $con = mysqli_connect("localhost", "root", "", "procurement-web-app");

          $query = "SELECT A.* FROM jobs A WHERE A.ID in (SELECT B.jobID FROM permissions B WHERE B.userID=". $_SESSION['user_id'] .")";
          $result = mysqli_query($con, $query);

          echo "<table>
                  <tr>
                  <th>Job ID</th>
                  <th>Job Name</th>
                  </tr>";
          while ($row = mysqli_fetch_array($result))
          {
            echo "<tr>";
            echo "<td>". $row['ID'] . "</td>";
            echo "<td><a class='job-link' href='#'>". $row['name']  . "</a></td>";
            echo "</tr>";
          }
          echo "</table>";        
        ?>
      </div>
    </main>


    <footer class="bottom-nav">
      <p>Copyright 2020</p>
    </footer>
  </body>
</html>