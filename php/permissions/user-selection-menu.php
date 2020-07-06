<?php
$select = "SELECT *";
$from = "FROM users";

$query = $select.$from;

$result = mysqli_query($con, $query);

echo "
  <table>
    <thead>
      <tr>
        <th>Username</th>
      </tr>
    </thead>
    <tbody>";

while ($row = mysqli_fetch_array($result)) {
echo '
      <tr>
      <td>'.                          $row["username"]                  .'</td>
      </tr>'
;
}

echo "
    </tbody>
  </table>"
;

?>
