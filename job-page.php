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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <title>Amteck Procurement</title>
</head>


<body>
  <?php include('php/header.php'); ?>





  <main class="main-area">
    <div class="content-container" id="report-selection">
      <table>
        <thead>
          <tr>
            <th id="job-title"><?php echo $_GET['job']?></th>
          </tr>
        </thead>
        <tbody>
          <tr id="Budgeted Amounts">
            <td class="report-button">
              <form action="job-page.php" method="get">
                <input type="checkbox" name="job"    value="<?php echo $job ?>" checked hidden>
                <input type="submit"   name="report" value="Budgeted Amounts">
              </form>
            </td>
          </tr>
          <tr id="Purchased Amounts">
            <td class="report-button">
              <form action="job-page.php" method="get">
                <input type="checkbox" name="job"    value="<?php echo $job ?>" checked hidden>
                <input type="submit"   name="report" value="Purchased Amounts">
              </form>
            </td>
          </tr>
          <tr id="Rental Tracker">
            <td class="report-button">
              <form action="job-page.php" method="get">
                <input type="checkbox" name="job"    value="<?php echo $job ?>" checked hidden>
                <input type="submit"   name="report" value="Rental Tracker">
              </form>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="main-display content-container" id="report-display">
      <?php
      switch($_GET['report']) {
        case "Budgeted Amounts" :
          include('php/job-page/budgeted-amounts-query.php');
        break;

        case "Purchased Amounts" :
          include('php/job-page/purchased-amounts-query.php');
        break;

        case "Rental Tracker" :
          include('php/job-page/rental-tracker-query.php');
        break;
      }
      ?>
    </div>

    <!-- Filter Inputs Underneath Data Container -->
    <div class="content-container" id="filter-bar">
      <form action="job-page.php" method="GET" id="filter-bar-form">
        <?php
          // Hidden input for job#
          echo '
          <input type="checkbox"
            name="job"
            value="'. $_GET["job"] .'"
            checked
            hidden>';
          // Hidden input for report currently being displayed
          echo '
            <input type="checkbox"
              name="report"
              value="'. $_GET["report"] .'"
              checked
              hidden>';

          // Sort box for item code, if set -> make placeholder appear | else -> leave empty
          if (isset($_GET['code'])) {
            echo 'Code:<input type="text" autofocus="autofocus" onfocus="this.select()" name="code" placeholder="'.$_GET['code'].'"> ';
          } else {
            echo 'Code:<input type="text" name="code"> ';
          }

          // Sort box for item description, if set -> make placeholder appear | else -> leave empty
          if (isset($_GET['description'])) {
            echo 'Description:<input type="text" name="description" placeholder="'.$_GET['description'].'"> ';
          } else {
            echo 'Description:<input type="text" name="description"> ';
          }

          // Sort box for item times bought, if set -> make placeholder appear | else -> leave empty
          if (isset($_GET['times'])) {
            echo 'Times Purchased:<input type="text" name="times" placeholder="'.$_GET['times'].'"> ';
          } else {
            echo 'Times Purchased:<input type="text" name="times"> ';
          }
        ?>
        <input type="submit" value="Filter" id="submit">
      </form>
    </div>

  </main>





  <?php include('php/footer.php') ?>
  <?php
  echo '<script>document.getElementById("'.$_GET['report'].'").style.backgroundColor = "gray"</script>';
  ?>
</body>

</html>