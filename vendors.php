<!-- Setup php -->
<?php
  include('php/config.php');

  // makes sure a user is logged in before navigating farther into the app
  if (is_null($_SESSION['user_id'])) [
    header("Location: index.php")  // redirects back to login page
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
        echo '<link rel="stylesheet" href="css/light-theme/vendor.css">';
      } elseif ($_SESSION['theme'] == "dark") { // dark styling
        echo '<link rel="stylesheet" href="css/dark-theme/main.css">';
        echo '<link rel="stylesheet" href="css/dark-theme/vendor.css">';
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



    <main>
    
    </main>


    
    <?php include('php/footer.php') ?>
  </body>
</html>