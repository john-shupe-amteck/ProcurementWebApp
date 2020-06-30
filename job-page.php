<?php
  include('php/config.php');
  
  $job = $_GET['job'];

  if (is_null($_SESSION['user_id'])) [
    header("Location: index.php")
  ];
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
        echo '<link rel="stylesheet" href="css/light-theme/job-page.css">';
      } elseif ($_SESSION['theme'] == "dark") { // dark styling
        echo '<link rel="stylesheet" href="css/dark-theme/main.css">';
        echo '<link rel="stylesheet" href="css/dark-theme/job-page.css">';
      }
    ?>
  <!-- link to amteck logo for tab -->
  <link rel="icon" href="img/amtecklogo.PNG">
  <!-- link for googl fonts TODO: move into the main .css -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

  <title>Amteck Procurement</title>
</head>


<body>
  <?php include('php/header.php'); ?>





  <main class="main-area">
    <div class="content-container" id="report-selection">
      <table>
        <tr>
          <th>Reports</th>
        </tr>
        <tr id="Release Tracker">
          <td class="report-button">
            <form action="job-page.php" method="get">
              <input type="checkbox" name="job" value="<?php echo $job ?>" checked hidden>
              <input type="submit" name="report" value="Release Tracker">
            </form>
          </td>
        </tr>
        <tr id="Budgeted Amounts">
          <td class="report-button">
            <form action="job-page.php" method="get">
              <input type="checkbox" name="job" value="<?php echo $job ?>" checked hidden>
              <input type="submit" name="report" value="Budgeted Amounts">
            </form>
          </td>
        </tr>
      </table>

    </div>

    <div class="content-container" id="report-display">
      <?php 
      switch($_GET['report']) {
        case "Release Tracker" :
          include 'php/job-page/release-tracker-query.php';            
        break;
        
        case "Budgeted Amounts" :
          include 'php/job-page/budgeted-amounts-query.php';
        break;
      }
      ?>
    </div>
  </main>





<footer class="bottom-nav">
  <p>Copyright 2020</p>
</footer>
<script src="app.js"></script>
<?php
  echo '<script>document.getElementById("'.$_GET['report'].'").style.backgroundColor = "gray"</script>';
  ?>
</body>

</html>