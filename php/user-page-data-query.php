<?php
  if (isset($_GET['job'])) {
    $job = $_GET['job'];
  }
  if (isset($_GET['job_name'])) {
    $job_name = $_GET['job_name'];
  }
  
  // Budgeted Query
  $select = "CREATE OR REPLACE VIEW budgeted AS
    SELECT
      `procurement-web-app`.`budget-details`.`itemID`           AS `itemID`,
      MAX(`procurement-web-app`.`budget-details`.`description`) AS `descrip`,
      SUM(`procurement-web-app`.`budget-details`.`quantity`)    AS `quantity`,
      MAX(`procurement-web-app`.`budget-details`.`cost-unitID`) AS `unit`,
      AVG(`procurement-web-app`.`budget-details`.`unit-cost`)   AS `cost`
    FROM `procurement-web-app`.`budget-details` "
  ;
  if (isset($code) && $code != "Select Sort Code") {
    $where ="
      WHERE
        `procurement-web-app`.`budget-details`.`budgetID` IN (
          SELECT
            `procurement-web-app`.`budgets`.`ID`
          FROM
            `procurement-web-app`.`budgets`
          WHERE
            `procurement-web-app`.`budgets`.`jobID` = '".$job."' AND
            `budget-details`.`sort-codeID`=".$code."
        )"
    ;
  } else {
    $where = "
      WHERE
        `procurement-web-app`.`budget-details`.`budgetID` IN (
          SELECT
            `procurement-web-app`.`budgets`.`ID`
          FROM
            `procurement-web-app`.`budgets`
          WHERE
            `procurement-web-app`.`budgets`.`jobID` = '".$job."'
        )"
    ;
  }

  // Run Budget Query
  $group_by = "GROUP BY `procurement-web-app`.`budget-details`.`itemID` ";
  $order_by = "ORDER BY SUM(`procurement-web-app`.`budget-details`.`quantity`) DESC";
  $query = $select.$where.$group_by.$order_by;
  mysqli_query($con, $query);
  
  // Purchased Query Setup
  $select = "CREATE OR REPLACE VIEW purchased AS
    SELECT
      `procurement-web-app`.`purchase-details`.`itemID`           AS `itemID`,
      sum(`procurement-web-app`.`purchase-details`.`quantity`)    AS `quantity`,
      max(`procurement-web-app`.`purchase-details`.`cost-unitID`) AS `unit`,
      avg(`procurement-web-app`.`purchase-details`.`unit-cost`)   AS `cost`
    FROM
      `procurement-web-app`.`purchase-details`"
  ;

  if (isset($code) && $code != "Select Sort Code") {
    $where = "
      WHERE
        `procurement-web-app`.`purchase-details`.`jobID` = '".$job."' AND
        `purchase-details`.`sort-codeID` = ".$code." "
    ;
  } else {
    $where = "
      WHERE
        `procurement-web-app`.`purchase-details`.`jobID` = '".$job."' "
    ;
  }

  // Run Purchased query
  $group_by = "GROUP BY `procurement-web-app`.`purchase-details`.`itemID`";
  $order_by = "ORDER BY sum(`procurement-web-app`.`purchase-details`.`quantity`) desc";
  $query = $select.$where.$group_by.$order_by;
  mysqli_query($con, $query);

  if (isset($description) && $description != "Partial Description") {
    $query = "SELECT * FROM procurementreport WHERE name like '%".$description."%' order by itemID";
  } else {
    $query = "SELECT * FROM procurementreport order by itemID";
  }

  $result = mysqli_query($con, $query);

  while ($row = mysqli_fetch_array($result)) {
    echo "
      <tr>
        <td class='item-name       monospace'>".$row['name']."</td>
        <td class='budget-quantity monospace'>".number_format($row['budqty'])."</td>
        <td class='budget-cost     monospace'>$".number_format($row['budcost'],2) ."/". $row['budunit']."</td>";

    if ($row['poqty'] == 0) {
      echo "
        <td class='po-quantity     monospace'></td>";
    } else {
      echo "
        <td class='po-quantity     monospace'>".number_format($row['poqty'])."</td>";
    }

    if ($row['pocost'] == 0) {
      echo "
      <td class='po-cost           monospace'></td>";
    } else {
      echo "
      <td class='po-cost           monospace'>$".number_format($row['pocost'],2) ."/". $row['pounit']."</td>";
    }

    // color positive variance as normal
    if ($row['variance']>0){
      echo "
        <td class='variance        monospace'>".number_format($row['variance'])."</td>
      </tr>"
      ;
    // color negative variance red
    } elseif ($row['variance']<0) {
      echo "
        <td class='variance        monospace' style='color:red'>".number_format($row['variance'])."</td>
      </tr>";
    } else {
      echo "
      <td class='variance          monospace' style='color:red'></td>
    </tr>";
    }
  }
?>