<?php
$query = "SELECT
    A.description,
    sum(quantity) as qty,
    `unit-cost` 
  FROM `budget-details` A
  WHERE
    A.budgetID in (
      SELECT B.ID 
      FROM budgets B
      WHERE B.jobID='".$job."')
  GROUP BY
    itemID
  ORDER BY A.description ASC
  Limit 50"
;

$result = mysqli_query($con, $query);

echo "
<table>
  <thead>
    <tr>
      <th>Description</th>
      <th class='budgeted-amount-quantity'>Quantity   </th>
      <th class='budgeted-amount-cost'    >Cost       </th>
    </tr>
  </thead>
  <tbody>";
while ($row = mysqli_fetch_array($result))
{
echo '
    <tr>
    <td class="monospace">'.                          $row["description"]             .'</td>
    <td class="budgeted-amount-quantity monospace" style="text-align:right">'. number_format($row["qty"])      .'</td>
    <td class="budgeted-amount-cost monospace" style="text-align:right">'. number_format($row["unit-cost"]).'</td>
    </tr>';
}
echo "
  </tbody>
</table>";