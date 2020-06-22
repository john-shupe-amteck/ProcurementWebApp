<?php
include 'php/config.php';
include 'php/insert.php';
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
        <a href="#" class="dropbtn">Profile</a>
        <div class="dropdown-content">
          <a href="index.php">Logout</a>
          <a href="#">Link 2</a>
          <a href="#">Link 3</a>
        </div>
      </div>

      <a class="link" id="news" href="news.php">News</a>
      <a class="link" id="contact" href="contact.php">Contact</a>
      <a class="link" id="about" href="about.php">About</a>
    </header>


    <main class="login-form">
      <form action="insert.php" method="post">
        Value1: <input type="text" name="field1"/><br/>
        Value2: <input type="text" name = "field2" /><br/>
        Value3: <input type="text" name = "field3" /><br/>
        Value4: <input type="text" name = "field4" /><br/>
        Value5: <input type="text" name = "field5" /><br/>
        <input type="submit"/>
      </form>
    </main>


    <footer class="bottom-nav">
      <p>Copyright 2020</p>
    </footer>
  </body>
</html>