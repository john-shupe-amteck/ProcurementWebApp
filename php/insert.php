<?php
$field1 = $connection->real_escape_string($_POST['field1']);
$field2 = $connection->real_escape_string($_POST['field2']);
$field3 = $connection->real_escape_string($_POST['field3']);
$field4 = $connection->real_escape_string($_POST['field4']);
$field5 = $connection->real_escape_string($_POST['field5']);

$query = "INSERT INTO testing (col1, col2, col3, col4, col5)
VALUES ('{$field1}','{$field2}','{$field3}','{$field4}','{$field5}')";

$connection->query($query);
$connection->close();
?>

