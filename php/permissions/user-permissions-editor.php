<?php
$query = "SELECT ID FROM jobs";
$results = mysqli_query($con, $query);
?>

<table>
  <thead>
    <tr>
      <th style="vertical-align: bottom;">User</th>
      <?php    
      while ($row = mysqli_fetch_array($results)) {
        $id = $row['ID'];

        echo '<th class="rotate"><div><span>'.$id.'</span></div></th>';
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    $query = "SELECT username FROM users";
    $results = mysqli_query($con, $query);

    while ($row = mysqli_fetch_array($results)) {
      $username = $row['username'];

      echo '
        <tr>
          <td>'.$username.'</th>
        </tr>';
    }
    ?>
    
  </tbody>
</table>