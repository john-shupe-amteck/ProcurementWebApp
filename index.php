<?php
  include('php/config.php');
  session_start();

  if (isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = null;
  }
  
  if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $connection->prepare("SELECT * FROM users WHERE username=:username");
    $query->bindParam("username", $username, PDO::PARAM_STR);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
      echo "<script>window.alert('Incorrect Password');</script>";
    } else {
      if (password_verify($password, $result['password'])) {
        $_SESSION['user_id'] = $result['ID'];
        header("Location: user-page.php");
      } else {
        echo "<script>window.alert('Incorrect Password');</script>";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="img/amtecklogo.PNG">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet"> 

    <title>Amteck Procurement</title>
  </head>


  <body>
    <header class="top-nav">
      <a class="home-link"   href="index.php">   Amteck Procurement</a>
      <a class="link active" href="index.php">   Home</a>
      <a class="link"        href="news.php">    News</a>
      <a class="link"        href="contact.php"> Contact</a>
      <a class="link"        href="about.php">   About</a>
    </header>


    <main class="login-form">
      <div class="flex-container">
        <form action="" method="post" name="signin-form">
          <div class="form-header">
            <h2>Login</h2>
          </div>
          <div class="form-element">
            <input type="text" name="username" placeholder="username"/>
          </div>
          <div class="form-element">
            <input type="password" id="password" name="password" placeholder="password">
          </div>
          <div class="form-footer">
            <div class="form-element">
              <ul>
                <li><a href="register.php">sign-up</a></li><br>
                <li><a href="forgot-password.php">forgot password?</a></li>
              </ul>
            </div>
            <button type="submit" name="login" value="login">Submit</button>
          </div>
        </form>
    </div>
    </main>

    <footer class="bottom-nav">
      <p>Copyright 2020</p>
    </footer>
    <script src="app.js"></script>
  </body>
</html>