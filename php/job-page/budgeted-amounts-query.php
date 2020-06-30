<?php
$query = "SELECT
  A.description,sum(quantity) as qty,
  `unit-cost` FROM `budget-details` A 
WHERE
  A.budgetID in (SELECT B.ID FROM budgets B WHERE B.jobID='".$job."') 
GROUP BY 
  itemID
ORDER BY qty DESC
Limit 50";

$result = mysqli_query($con, $query);

echo "
<table>
  <thead>
    <tr>
      <th>Description</th>
      <th>Quantity</th>
      <th>Cost</th>
    </tr>
  </thead>
  <tbody>";
while ($row = mysqli_fetch_array($result))
{
echo '
    <tr>
      <td>'. $row["description"] .'</td>
      <td>'. $row["qty"].'</td>
      <td>'.$row["unit-cost"].'</td>
    </tr>';
}
echo "
  </tbody>
</table>";        