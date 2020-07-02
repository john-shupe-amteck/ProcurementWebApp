<?php

$query = "CREATE or REPLACE VIEW  purchased as
SELECT `procurement-web-app`.`purchase-details`.`itemID` AS `itemID`,
    sum(`procurement-web-app`.`purchase-details`.`quantity`)       AS `quantity`,
    max(`procurement-web-app`.`purchase-details`.`cost-unitID`)    AS `unit`,
    avg(`procurement-web-app`.`purchase-details`.`unit-cost`)      AS `cost` 
  from 
    `procurement-web-app`.`purchase-details` 
  where 
    `procurement-web-app`.`purchase-details`.`jobID` = '".$_GET['job']."' 
  group by 
    `procurement-web-app`.`purchase-details`.`itemID` 
  order by sum(`procurement-web-app`.`purchase-details`.`quantity`) desc"
;

mysqli_query($con, $query);

$query = "SELECT
    items.description as description,
    purchased.quantity as quantity
  FROM
    `purchased`
  left join items on purchased.itemID = items.ID
  WHERE not 
    description = ''"
;


$result = mysqli_query($con, $query);

echo "
  <table>
    <thead>
      <tr>
        <th>Description</th>
        <th>Quantity   </th>
      </tr>
    </thead>
    <tbody>";

while ($row = mysqli_fetch_array($result)) {
echo '
      <tr>
      <td class="monospace">'.                          $row["description"]             .'</td>
      <td class="monospace" style="text-align:right">'. number_format($row["quantity"])      .'</td>
      </tr>'
;
}

echo "
    </tbody>
  </table>"
;