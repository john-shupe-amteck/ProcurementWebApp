<?php

$select = "CREATE or REPLACE VIEW  purchased as
  SELECT `procurement-web-app`.`purchase-details`.`itemID` AS `itemID`,
    sum(`procurement-web-app`.`purchase-details`.`quantity`)       AS `quantity`,
    max(`procurement-web-app`.`purchase-details`.`cost-unitID`)    AS `unit`,
    avg(`procurement-web-app`.`purchase-details`.`unit-cost`)      AS `cost`";

$from = "
  from
    `procurement-web-app`.`purchase-details`";

if (isset($_GET['code']) && $_GET['code'] != "") {
  $where = "
  where
    `procurement-web-app`.`purchase-details`.`jobID` = '".$_GET['job']."' and
    `procurement-web-app`.`purchase-details`.`sort-codeID` = '".$_GET['code']."'";
} else {
  $where = "
  where
    `procurement-web-app`.`purchase-details`.`jobID` = '".$_GET['job']."'";
}

$group_by = "
  group by
    `procurement-web-app`.`purchase-details`.`itemID`";

$order_by = "
  order by itemID "
;

$query = $select.$from.$where.$group_by.$order_by;

mysqli_query($con, $query);


$select = "SELECT
    items.description as description,
    purchased.quantity as quantity";

$from = "
  FROM
    `purchased`
  left join items on purchased.itemID = items.ID";

if (isset($_GET['description']) && $_GET['description'] != "") {
  $where = "
  WHERE 
    description like '%".$_GET['description']."%' and
    not description = ''";
} else {
  $where = "
    WHERE not
      description = ''"
  ;
}


$query = $select.$from.$where;


$result = mysqli_query($con, $query);

echo "
  <table>
    <thead>
      <tr>
        <th>                 Description</th>
        <th class='quantity'>Quantity   </th>
      </tr>
    </thead>
    <tbody>";

while ($row = mysqli_fetch_array($result)) {
echo '
      <tr>
      <td class="         monospace">'.                          $row["description"]                  .'</td>
      <td class="quantity monospace" style="text-align:right">'. number_format($row["quantity"])      .'</td>
      </tr>'
;
}

echo "
    </tbody>
  </table>"
;