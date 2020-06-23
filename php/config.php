<?php
  session_start();

  define('USER', 'root');
  define('PASSWORD', '');
  define('HOST', 'localhost');
  define('DATABASE', 'procurement-web-app');
  
  try {
      $connection = new PDO("mysql:host=".HOST.";dbname=".DATABASE, USER, PASSWORD);
  } catch (PDOException $e) {
      exit("Error: " . $e->getMessage());
  }
  
  $_SESSION['theme'] = "dark";
?>